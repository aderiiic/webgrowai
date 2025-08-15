<?php

namespace App\Livewire\Dashboard;

use App\Jobs\PublishAiContentToWpJob;
use App\Jobs\PublishToFacebookJob;
use App\Jobs\PublishToInstagramJob;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
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
            // WP publiceringen återskapas via PublishAiContentToWpJob kräver site/status i inputs/payload.
            // För MVP: skicka som utkast.
            dispatch(new PublishAiContentToWpJob($pub->ai_content_id, $pub->content->site_id ?? 0, 'draft'))->onQueue('publish');
        } elseif ($pub->target === 'facebook') {
            dispatch(new PublishToFacebookJob($pub->id))->onQueue('social');
        } elseif ($pub->target === 'instagram') {
            dispatch(new PublishToInstagramJob($pub->id))->onQueue('social');
        }

        session()->flash('success', 'Kör om startad.');
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
