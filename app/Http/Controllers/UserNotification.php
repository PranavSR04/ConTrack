<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActivityLogs;
use App\Models\UserNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserNotification extends Controller
{
 
    public function getUserNotification(Request $request)
    {  
       
        // Takes all notification from the Notification table with user_id as $user_id
        // Find the count of all notification with status true
        // for each notification from the action_log table takes the details
        // Append the data into a finalnotification array
        // finaly append the active notification count
        // convert the array into a json file
        
        $validator=Validator::make($request->all(),[
            "sendto_id"=> "required",
        ]);
        if($validator->fails()){
            return response()->json(['message'=> $validator->errors()],422);
        }
        $user_id = $request->get('sendto_id');
        $activeNotificationsCount = UserNotifications::where('sendto_id', $user_id)
        ->where('status', 1)
        ->count();
        $notifications=UserNotifications::where("sendto_id",$user_id)
        ->orderByDesc("created_at")
        ->get();
        if($notifications->isEmpty())
            {
                return response()->json(['error'=>$notifications->errors()] ,404);
            }
        $finalnotifications=['active_notifications_count' => $activeNotificationsCount,
        'notifications' => []];
        foreach ($notifications as $notification)
        {
            $actionLog=ActivityLogs::find($notification->log_id);
            if($actionLog)
            {
                $notificationDetails=
                [
                    'log_id'=>$notification->log_id,
                    'contract_id'=>optional($actionLog)->contract_id,
                    'msa_id'=>optional($actionLog)->msa_id,
                    'performed_by'=>$actionLog->performed_by,
                    'action'=>$actionLog->action,
                ];
 
                $finalnotifications['notifications'][]=$notificationDetails;
            }
        }
        return response()->json(['notification'=> $finalnotifications ] ,200);
    }
    public function notificationStatusUpdate(Request $request){
        $validator=Validator::make($request->all(),[
            "user_id"=> "required",
        ]);
        if($validator->fails()){
            return response()->json(['message'=> $validator->errors()],422);
        }
        $user_id = $request->get('user_id');
        UserNotifications::where('sendto_id',$user_id)->update(['status'=>0]);
        return response()->json(["message"=> "updated"] ,200);
    }
}