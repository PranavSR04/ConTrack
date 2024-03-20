<?php

namespace App\Services;


use App\Models\ActivityLogs;
use App\Models\Contracts;
use App\Models\MSAs;
use App\Models\User;
use App\Models\UserNotifications;
use App\ServiceInterfaces\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class NotificationService implements NotificationInterface
{
    public function getUserNotification(Request $request)
    { 
    try {
        // Validation rules for the request parameters
        $validator = Validator::make($request->all(), [
            "sendto_id" => "required|numeric",
            "page" => "sometimes|numeric",
            "pageSize" => "sometimes|numeric",
        ]);
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user_id = $request->input('sendto_id');
        $pageSize = $request->input('pageSize', 10); 
        //check whether the user exists
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return response()->json(["error" => ["sendto_id" => "User ID not found."]], 404);
        }
        //get the total active notification count
        $activeNotificationsCount = UserNotifications::where('sendto_id', $user_id)
            ->where('status', 1)
            ->count();
        // Retrieve notifications for the user, paginated
        $notifications = UserNotifications::where("sendto_id", $user_id)
            ->orderByDesc("created_at")
            ->paginate($pageSize);

        if ($notifications->isEmpty()) {
            return response()->json(['error' => 'No notifications found.'], 404);
        }
        //final response structure
        $finalNotifications = [
            'total_notifications' => $notifications->total(),
            'current_page' => $notifications->currentPage(),
            'notifications_per_page' => $notifications->perPage(),
            'active_notifications_count' => $activeNotificationsCount,
            'NotificationListdisplay' => [],
        ];
        // Iterate through notifications to build the response
        foreach ($notifications as $notification) {
            $actionLog = ActivityLogs::find($notification->log_id);
            if ($actionLog) {
                $contract = Contracts::find($actionLog->contract_id);
                $msa = MSAs::find($actionLog->msa_id);
                $notificationDetails = [
                    'log_id' => $notification->log_id,
                    'contract_ref_id' => optional($contract)->contract_ref_id,
                    'contract_id'=>optional($contract)->id,
                    'msa_ref_id' => optional($msa)->msa_ref_id,
                    'msa_id'=>optional($msa)->id,
                    'client_name'=>$msa->client_name,
                    'performed_by' => $actionLog->performed_by,
                    'action' => $actionLog->action,
                    'updated_at'=>$actionLog->created_at,
                ];
                $finalNotifications["NotificationListdisplay"][] = $notificationDetails;
            }
        }
    
        return response()->json(['data' => $finalNotifications], 200);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function notificationStatusUpdate(Request $request){
    try{ 
        $validator=Validator::make($request->all(),[
            "user_id"=> "required|numeric",
        ]);
        if($validator->fails())
            {
                return response()->json(['message'=> $validator->errors()],405);//unprocessable
            }
        //change the status to 1
        $user_id = $request->get('user_id');
        $user = UserNotifications::where('sendto_id', $user_id)->first();
        if (!$user) 
            {
                return response()->json(['error' => ["sendto_id" => "User ID not found."]], 404);//not found
            }
        UserNotifications::where('sendto_id',$user_id)->update(['status'=>0]);
        return response()->json(["message"=> "updated"] ,200);//ok
    }catch(Exception $e)
    {
        return response()->json(["message"=> $e->getMessage()],500);//internal server error
    }
}
}