<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateLinkedInSuggestionsJob;
use App\Jobs\PublishToLinkedInJob;
use App\Models\ContentPublication;
use App\Models\PostSuggestion;
use App\Support\CurrentCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LinkedInSuggestionController extends Controller
{
    public function index(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $items = PostSuggestion::query()
            ->where('customer_id', $customer->id)
            ->where('provider', 'linkedin')
            ->where('expires_at', '>', now())
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }

    public function store(Request $request, CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $data = $request->validate([
            'topic' => 'required|string|max:200',
            'tone' => 'nullable|string|max:100',
            'count' => 'nullable|integer|min:1|max:5',
        ]);

        dispatch(new GenerateLinkedInSuggestionsJob(
            customerId: $customer->id,
            topic: $data['topic'],
            tone: $data['tone'] ?? null,
            count: $data['count'] ?? 1
        ))->onQueue('ai');

        return response()->json([
            'status' => 'queued',
            'message' => 'Förslag genereras och visas i max 5 dagar.',
        ]);
    }

    public function destroy(PostSuggestion $suggestion, CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer && $suggestion->customer_id === $customer->id, 403);

        $suggestion->delete();
        return response()->json(['status' => 'ok']);
    }

    public function publish(Request $request, CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $data = $request->validate([
            'text' => 'required|string',
            'schedule_at' => 'nullable|date',
            'image_prompt' => 'nullable|string|max:500',
        ]);

        $scheduledAt = !empty($data['schedule_at']) ? Carbon::parse($data['schedule_at']) : null;

        $pub = ContentPublication::create([
            'ai_content_id' => $request->integer('ai_content_id') ?: null, // valfritt koppla mot AI-content
            'target'        => 'linkedin',
            'status'        => 'queued',
            'scheduled_at'  => $scheduledAt,
            'message'       => null,
            'payload'       => [
                'text' => $data['text'],
                'image_prompt' => $data['image_prompt'] ?? null,
            ],
        ]);

        if (!$scheduledAt || $scheduledAt->isPast()) {
            dispatch(new PublishToLinkedInJob($pub->id))->onQueue('social');
        }

        return response()->json([
            'status' => 'queued',
            'message' => $scheduledAt && $scheduledAt->isFuture()
                ? 'Publicering schemalagd.'
                : 'Publicering kölagd.',
        ]);
    }
}
