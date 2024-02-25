<?php

namespace App\Console\Commands;

use App\Models\ExperionEmployees;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users data with updated data from employee table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
        //$updatedEmployees = ExperionEmployees::where('updated_at', '>', now()->subDay())->get(); fetch updated data only
        $allEmployees = ExperionEmployees::all();
      
        foreach ($allEmployees as $employee) {
            $user = User::where('experion_id', $employee->id)->first();
            $fullName = $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name;
            if ($user) {
                $user->update([
                    'user_name' => $fullName,
                    'user_mail' => $employee->email_id
                ]);
            }
        }

        $this->info('User data updated successfully.');
        Log::info('User data updated successfully.');
    }
     catch (Exception $e) {
        $this->error('Error updating user data: ' . $e->getMessage());
        Log::error('Error updating user data: ' . $e->getMessage()); 
    
     }
    }
}
