<?php

namespace App\Livewire\Dashboard;

use App\Jobs\PublishAiContentToWpJob;
use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PublicationsIndex extends Component
{
    use WithPagination;

    public string $target = ''; // ''|wp|facebook|instagram
    public string $status = ''; // ''|queued|processing|published|failed

    public function retry(int $id): void
    {
        $pub = ContentPublication::with('content')->findOrFail($id);

        if ($pub->status !== 'failed') {
            session()->flash('success', 'Endast misslyckade kan köras om.');
            return;
        }

        $pub->update(['status' => 'queued', 'message' => null]);

        if ($pub->target === 'wp') {
            dispatch(new PublishAiContentToWpJob(
                aiContentId: $pub->ai_content_id,
                siteId: $pub->content->site_id ?? 0,
                status: 'draft',
                scheduleAtIso: null,
                publicationId: $pub->id
            ))->onQueue('publish');
        } elseif ($pub->target === 'facebook') {
            dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
        } elseif ($pub->target === 'instagram') {
            dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
        }

        session()->flash('success', 'Kör om startad.');
    }

    public function cancel(int $id): void
    {
        $pub = ContentPublication::with('content')->findOrFail($id);

        // Ägarskap/behörighet: admin eller samma kund
        $user = Auth::user();
        $current = app(CurrentCustomer::class)->get();
        if (!$user?->isAdmin()) {
            abort_unless($current && $pub->content && $pub->content->customer_id === $current->id, 403);
        }

        if ($pub->status !== 'queued') {
            session()->flash('success', 'Endast köade publiceringar kan avbrytas.');
            return;
        }

        $pub->update([
            'status'  => 'canceled',
            'message' => 'Avbruten av användare ' . ($user?->name ?: 'okänd') . ' ' . now()->format('Y-m-d H:i'),
        ]);

        session()->flash('success', 'Publiceringen avbröts.');
        // Om du cachar listan lokalt, trigga om-laddning här; annars räcker render()-frågan
    }

    public function render(CurrentCustomer $current)
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $q = ContentPublication::query()
            ->whereHas('content', fn($c) => $c->where('customer_id', $customer->id))
            ->with('content:id,title');

        if ($this->target !== '') $q->where('target', $this->target);
        if ($this->status !== '') $q->where('status', $this->status);

        $pubs = $q->latest()->paginate(20);

        return view('livewire.dashboard.publications-index', [
            'pubs' => $pubs,
        ]);
    }
}
