<?php

namespace App\Http\Controllers;

use App\Models\ImageAsset;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image as ImageManager;
use Illuminate\Support\Str;

class ImageAssetController extends Controller
{
    public function index(Request $req)
    {
        Gate::authorize('viewAny', ImageAsset::class);

        // Hämta aktiv kund från CurrentCustomer, fallback till user->customer_id
        $selectedCustomerId = (int) (app(\App\Support\CurrentCustomer::class)->get()?->id ?? ($req->user()?->customer_id ?? 0));

        // Om ingen kund är vald och användaren är admin: returnera tom lista tills admin väljer kund
        if ($selectedCustomerId <= 0) {
            return response()->json([
                'data' => [],
                'meta' => ['current_page' => 1, 'last_page' => 1, 'total' => 0],
            ]);
        }

        $q = ImageAsset::query()->where('customer_id', $selectedCustomerId);

        if ($search = trim((string) $req->query('q', ''))) {
            $q->where('original_name', 'like', '%'.$search.'%');
        }

        $assets = $q->orderByDesc('created_at')->paginate(24);
        return response()->json($assets);
    }

    public function store(Request $req)
    {
        Gate::authorize('create', ImageAsset::class);

        $data = $req->validate([
            'file' => ['required','file','max:3072', 'mimes:jpeg,jpg,png,webp'],
        ]);

        $file = $data['file'];

        // Samma kundkäll-logik som i index
        $selectedCustomerId = (int) (app(\App\Support\CurrentCustomer::class)->get()?->id ?? ($req->user()?->customer_id ?? 0));
        abort_if($selectedCustomerId <= 0, 403, 'Saknar aktiv kund.');

        $ext  = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $uuid = (string) Str::uuid();
        $key  = "customers/{$selectedCustomerId}/images/".now()->format('Y/m/').$uuid.'.'.$ext;

        $imgInfo = @getimagesize($file->getPathname());
        $width   = $imgInfo[0] ?? null;
        $height  = $imgInfo[1] ?? null;

        Storage::disk('s3')->put($key, file_get_contents($file->getPathname()), [
            'visibility' => 'private',
            'ContentType' => $file->getMimeType() ?: 'application/octet-stream'
        ]);

        $thumbKey = "customers/{$selectedCustomerId}/images/thumbs/".now()->format('Y/m/').$uuid.'.webp';
        try {
            $thumb = ImageManager::read($file->getPathname())
                ->cover(256, 256)
                ->toWebp(85);
            Storage::disk('s3')->put($thumbKey, (string)$thumb, ['visibility' => 'private', 'ContentType' => 'image/webp']);
        } catch (\Throwable) {
            $thumbKey = null;
        }

        $asset = ImageAsset::create([
            'customer_id'   => $selectedCustomerId,
            'uploaded_by'   => $req->user()->id ?? null,
            'disk'          => 's3',
            'path'          => $key,
            'thumb_path'    => $thumbKey,
            'original_name' => $file->getClientOriginalName(),
            'mime'          => $file->getMimeType(),
            'size_bytes'    => $file->getSize() ?: 0,
            'width'         => $width,
            'height'        => $height,
            'sha256'        => hash_file('sha256', $file->getPathname()),
        ]);

        return response()->json($asset, 201);
    }

    public function thumb(Request $req, ImageAsset $asset)
    {
        Gate::authorize('view', $asset);

        $disk = Storage::disk($asset->disk);
        $key  = $asset->thumb_path ?: $asset->path;

        $stream = $disk->readStream($key);
        if (!$stream) abort(404);

        return response()->stream(function() use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'  => $asset->thumb_path ? 'image/webp' : ($asset->mime ?: 'image/jpeg'),
            'Cache-Control' => 'private, max-age=60',
        ]);
    }
}
