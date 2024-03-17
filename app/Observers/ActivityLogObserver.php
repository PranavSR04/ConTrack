<?php

namespace App\Observers;

use App\Mail\SendMailNotification;
use App\Models\ActivityLogs;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Log;
class ActivityLogObserver
{
    /**
     * Handle the ActivityLogs "created" event.
     */
    public function created(ActivityLogs $activityLogs)
    {
        $users=User::all();
        $failedEmails = [];
        foreach ($users as $user) {
            
                Mail::to($user->user_mail)->send(new SendMailNotification($activityLogs));
                Log::info('Email sent successfully to: ' . $user->user_mail);
           
        }
            if(!empty($failedEmails)) {
                
                Log::warning('Failed to send emails to: ' . implode(', ', $failedEmails));
            }
            else{
                Log::info('All emails sent successfully');
            }
           
    }

    /**
     * Handle the ActivityLogs "updated" event.
     */
    public function updated(ActivityLogs $activityLogs): void
    {
        //
    }

    /**
     * Handle the ActivityLogs "deleted" event.
     */
    public function deleted(ActivityLogs $activityLogs): void
    {
        //
    }

    /**
     * Handle the ActivityLogs "restored" event.
     */
    public function restored(ActivityLogs $activityLogs): void
    {
        //
    }

    /**
     * Handle the ActivityLogs "force deleted" event.
     */
    public function forceDeleted(ActivityLogs $activityLogs): void
    {
        //
    }
}
