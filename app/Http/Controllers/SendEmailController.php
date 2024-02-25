<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMail;

class SendEmailController extends Controller
{
    public function sendEmail()
    {
        // Fetch the latest log entry
        $latestLog = ActivityLogs::latest()->first();
       
        if (!$latestLog) {
            return response()->json(['message' => 'No log entries found'], 404);
        }

        $username = $latestLog->user->user_name;
        $contract_ref_id= $latestLog->contract->contract_ref_id;
        $msa_ref_id= $latestLog->msa->msa_ref_id;
        $users = User::all();
        $responses = []; // Initialize an array to store responses for each user

        // Check if there are users
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }

        // Iterate through users and send email
        foreach ($users as $user) {
            
                Mail::to($user->user_mail)->send(new NotifyMail($latestLog->contract_id, $latestLog->msa_id,$username,$latestLog->action));
                $responses[] = "Email successfully sent to {$user->user_mail}";
            
        }

        // Return a JSON response with all the individual user responses
        return response()->json(['messages' => $responses], 200);
    }
}
