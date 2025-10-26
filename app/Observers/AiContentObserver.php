<?php

namespace App\Observers;

use App\Models\AiContent;
use Illuminate\Support\Facades\Log;

class AiContentObserver
{
    public function updated(AiContent $aiContent): void
    {
        // Update bulk generation progress when content status changes
        if ($aiContent->bulk_generation_id && $aiContent->isDirty('status')) {
            $bulk = $aiContent->bulkGeneration;

            if (!$bulk) {
                return;
            }

            $completedCount = $bulk->contents()
                ->whereIn('status', ['ready', 'published'])
                ->count();

            $bulk->update(['completed_count' => $completedCount]);

            // Check if all are done
            if ($completedCount >= $bulk->total_count) {
                $bulk->update(['status' => 'done']);
                Log::info("[BulkGen] Alla texter färdiga", ['bulk_id' => $bulk->id]);
            }

            // Check for failures
            $failedCount = $bulk->contents()->where('status', 'failed')->count();
            if ($failedCount > 0 && ($completedCount + $failedCount) >= $bulk->total_count) {
                $bulk->update(['status' => 'done']); // Mark as done even with some failures
            }
        }
    }
}
