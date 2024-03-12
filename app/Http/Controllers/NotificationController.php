<?php
 
namespace App\Http\Controllers;
 
use App\Models\ActivityLogs;
use App\Models\Contracts;
use App\Models\MSAs;
use App\Models\User;
use App\Models\UserNotifications;
use App\ServiceInterfaces\NotificationInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
class NotificationController extends Controller
{
    private $notificationService;
    public function __construct(NotificationInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function getUserNotification(Request $request)
    {  
       
    return $this->notificationService->getUserNotification($request);
}
    
    public function notificationStatusUpdate(Request $request){
        return $this->notificationService->notificationStatusUpdate($request);
    }
}