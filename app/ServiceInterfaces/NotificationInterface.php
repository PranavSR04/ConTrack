<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface NotificationInterface
{
    public function getUserNotification(Request $request);
    public function notificationStatusUpdate(Request $request);
    
}