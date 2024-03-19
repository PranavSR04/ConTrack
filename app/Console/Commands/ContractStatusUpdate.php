<?php

namespace App\Console\Commands;

use App\Models\Contracts;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ContractStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:contract-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
    // Update status to "On Progress" for contracts starting today
    Contracts::where('start_date','<=', today())
    ->where('contract_status', '!=', 'On Progress')
    ->where('contract_status', '!=', 'Expiring')
    ->where('contract_status', '!=', 'Closed') // Only update if status isn't closed
    ->update(['contract_status' => 'On Progress']);

    //Update status to "Expired" for contracts where end date has passed
    Contracts::whereDate('end_date', '<', today())
        ->where('contract_status', '!=', 'Closed')
        ->where('contract_status', '!=', 'Expired')
        ->update(['contract_status' => 'Expired']);

    //Update status to "Expiring" for contracts where end date is within 2 weeks
        Contracts::whereBetween('end_date', [today() , today()->addDays(14)])
        ->where('contract_status', '!=', 'Closed')
        ->where('contract_status', '!=', 'Expiring')
        ->update(['contract_status' => 'Expiring']);

        $this->info('Contract status updated succesfully.');
        Log::info('Contract status updated succesfully.');
        }
        catch (Exception $e) {
            $this->error('Error updating contract status: ' . $e->getMessage());
            Log::error('Error updating contract status: ' . $e->getMessage()); 
        
         }
    }
    
}
