<?php

namespace App\Console\Commands;

use App\Models\Survey;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredSurveys extends Command
{
    protected $signature = 'surveys:deactivate-expired';
    protected $description = 'Deactivate surveys that have passed their expiry date';

    public function handle(): int
    {
        $count = Survey::query()
            ->where('is_active', true)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['is_active' => false]);

        $this->info("Deactivated {$count} expired survey(s).");
        Log::info("surveys:deactivate-expired: deactivated {$count} survey(s).");

        return Command::SUCCESS;
    }
}
