<?php

namespace App\Listeners;

use App\Models\AiContent;
use Illuminate\Support\Facades\Log;

class UpdateBulkGenerationProgress
{
    public function handle(object $event): void
    {
        // This can be triggered from GenerateContentJob when status is updated
        // For now, we'll check in the AiContent model observer
    }
}
