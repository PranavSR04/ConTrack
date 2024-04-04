<?php

namespace App\Jobs;

use App\Mail\SendMailNotification;
use App\Models\ActivityLogs;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendActivityLogEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $activityLogs;
    /**
     * Create a new job instance.
     */
    public function __construct(ActivityLogs $activityLogs)
    {
        $this->activityLogs = $activityLogs;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->user_mail)->send(new SendMailNotification($this->activityLogs));
            }
        } catch (Exception $e) {
            // Log any exceptions if needed
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
    }
}
