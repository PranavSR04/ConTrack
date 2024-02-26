<?php

namespace App\Http\Controllers;
use App\Models\ActivityLogs;
use App\Models\UserNotifications;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function insertData(){
        $activityLogDataArray=[
            [
                "msa_id"=>1,
                "performed_by"=>5,
                "action"=>"edit"
            ],
        ] ;
        
        foreach ($activityLogDataArray as $logData) {
            $logData = new ActivityLogs($logData);
            $logData->save();
        }
        return response()->json(['message' => 'Data entered'], 200);
       
    }
}

