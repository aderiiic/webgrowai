<?php

namespace App\Console\Commands;

use App\Models\PostSuggestion;
use Illuminate\Console\Command;

class PurgeExpiredSuggestions extends Command
{
    protected $signature = 'suggestions:purge-expired';
    protected $description = 'Rensar inläggsförslag vars expires_at har passerat';

    public function handle(): int
    {
        $count = PostSuggestion::where('expires_at', '<=', now())->delete();
        $this->info("Raderade {$count} förslag.");
        return self::SUCCESS;
    }
}
