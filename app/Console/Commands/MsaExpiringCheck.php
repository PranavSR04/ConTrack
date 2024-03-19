<?php

namespace App\Console\Commands;

use App\Models\MSAs;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MsaExpiringCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:msa-expiring-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To change MSA active status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
    //Update status to "Expired" for contracts where end date has passed
    MSAs::whereDate('end_date', '<', today())
        ->where('is_active', '!=', 0)
        ->update(['is_active' => 0]);

        $this->info('Msa Expiry updated succesfully.');
        Log::info('Msa Expiry updated succesfully.');
        }
        catch (Exception $e) {
            $this->error('Error updating MSA status: ' . $e->getMessage());
            Log::error('Error updating MSA status: ' . $e->getMessage()); 
        
         }
    }
}
