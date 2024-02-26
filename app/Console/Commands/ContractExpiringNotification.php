<?php

namespace App\Console\Commands;

use App\Models\ActivityLogs;
use App\Models\Contracts;
use App\Models\UserNotifications;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ContractExpiringNotification extends Command
{
    protected $signature = 'check-contract-end-dates';
    protected $description = 'Check contract end dates and send notifications for contracts expiring within 2 weeks.';

    public function handle()
    {
        try {
        // Get contracts ending within 2 weeks
        $contracts = Contracts::where('end_date', '=', today()->addDays(14))
            ->get();
        if (!$contracts->isEmpty()) {
        foreach ($contracts as $contract) {
            ActivityLogs::create([
                'contract_id' => $contract->id,
                'msa_id' => $contract->msa_id,
                'action' => 'Expiring '
            ]);
        }
    }
        $contracts = Contracts::where('end_date', '=', today()->subDays(1))
            ->get();
        if (!$contracts->isEmpty()) {
        foreach ($contracts as $contract) {
            ActivityLogs::create([
                'contract_id' => $contract->id,
                'msa_id' => $contract->msa_id,
                'action' => 'Expired'
            ]);
        }
    }
         $this->info('Contract expiring notification sent to Activitylog.');
         Log::info('Contract expiring notification sent to Activitylog.');
}
catch (Exception $e) {
    $this->error('Error updating expiring status: ' . $e->getMessage());
    Log::error('Error updating expiring status: ' . $e->getMessage()); 

 }
    }
}