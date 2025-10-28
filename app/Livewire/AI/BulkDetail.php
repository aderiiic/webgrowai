<?php

namespace App\Livewire\AI;

use App\Models\BulkGeneration;
use App\Models\ContentPublication;
use App\Support\CurrentCustomer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class BulkDetail extends Component
{
    public BulkGeneration $bulk;
    public array $selectedContents = [];
    public string $publishStatus = 'draft';
    public bool $showConfirmModal = false;
    public bool $isPublishing = false;
    public array $publishingProgress = [];

    public function mount(int $id, CurrentCustomer $current): void
    {
        $customer = $current->get();
        abort_unless($customer, 403);

        $this->bulk = BulkGeneration::with(['contents' => function ($q) {
            $q->orderBy('batch_index');
        }])->findOrFail($id);

        abort_unless((int)$this->bulk->customer_id === (int)$customer->id, 403);
    }

    public function refreshData(): void
    {
        $this->bulk->refresh();
        $this->bulk->load(['contents' => function ($q) {
            $q->orderBy('batch_index');
        }]);

        // Uppdatera publiceringsprogress om vi är i publishing-läge
        if ($this->isPublishing && !empty($this->publishingProgress)) {
            $this->checkPublishingProgress();
        }
    }

    private function checkPublishingProgress(): void
    {
        $allCompleted = true;

        foreach ($this->publishingProgress as $contentId => $currentStatus) {
            // Skippa om redan klar
            if (in_array($currentStatus, ['published', 'failed'])) {
                continue;
            }

            // Kolla senaste publication för denna content
            $publication = ContentPublication::where('ai_content_id', $contentId)
                ->latest()
                ->first();

            if ($publication) {
                $this->publishingProgress[$contentId] = $publication->status;

                // Om inte klar än, fortsätt tracka
                if (!in_array($publication->status, ['published', 'failed'])) {
                    $allCompleted = false;
                }
            } else {
                $allCompleted = false;
            }
        }

        // Om alla är klara, stäng av publishing-läget
        if ($allCompleted) {
            $this->isPublishing = false;

            $successCount = count(array_filter($this->publishingProgress, fn($s) => $s === 'published'));
            $failedCount = count(array_filter($this->publishingProgress, fn($s) => $s === 'failed'));

            if ($successCount > 0) {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => "{$successCount} texter publicerade! " . ($failedCount > 0 ? "{$failedCount} misslyckades." : '')
                ]);
            }
        }
    }

    public function toggleSelection(int $contentId): void
    {
        if (in_array($contentId, $this->selectedContents)) {
            $this->selectedContents = array_values(array_diff($this->selectedContents, [$contentId]));
        } else {
            $this->selectedContents[] = $contentId;
        }
    }

    public function selectAll(): void
    {
        $this->selectedContents = $this->bulk->contents
            ->filter(fn($c) => in_array($c->status, ['ready', 'completed']))
            ->pluck('id')
            ->toArray();
    }

    public function deselectAll(): void
    {
        $this->selectedContents = [];
    }

    public function openConfirmModal(): void
    {
        if (empty($this->selectedContents)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Välj minst en text att publicera'
            ]);
            return;
        }

        $this->showConfirmModal = true;
    }

    public function confirmPublish(): void
    {
        if (empty($this->selectedContents)) {
            $this->showConfirmModal = false;
            return;
        }

        if (!$this->bulk->site_id) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Ingen webbplats vald för denna bulk-generering'
            ]);
            $this->showConfirmModal = false;
            return;
        }

        // Starta publishing-läget
        $this->isPublishing = true;
        $this->publishingProgress = [];

        // Initiera status för varje vald text
        foreach ($this->selectedContents as $contentId) {
            $this->publishingProgress[$contentId] = 'queued';
        }

        // Dispatcha jobb för varje vald text
        foreach ($this->selectedContents as $contentId) {
            dispatch(new \App\Jobs\PublishAiContentToWpJob(
                aiContentId: $contentId,
                siteId: $this->bulk->site_id,
                status: $this->publishStatus,
                scheduleAtIso: null,
                publicationId: null
            ))->afterCommit()->onQueue('publish');
        }

        $statusText = $this->publishStatus === 'publish' ? 'publiceras direkt' : 'sparas som utkast';

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => count($this->selectedContents) . " texter {$statusText} nu..."
        ]);

        $this->showConfirmModal = false;
        $this->selectedContents = [];
    }

    public function render(): View
    {
        $this->refreshData();

        return view('livewire.a-i.bulk-detail');
    }
}
