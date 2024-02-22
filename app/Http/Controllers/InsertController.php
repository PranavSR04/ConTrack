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
                "contract_id"=> 1,
                "msa_id"=>null,
                "performed_by"=>1,
                "action"=>"added"
            ],
            [
                "contract_id"=> 1,
                "msa_id"=>null,
                "performed_by"=>1,
                "action"=>"edited"
            ],
            [
                "contract_id"=>null,
                "msa_id"=>1,
                "performed_by"=>1,
                "action"=>"added"
            ],
            [
                "contract_id"=> 2,
                "msa_id"=>null,
                "performed_by"=>1,
                "action"=>"edited"
            ],
            [
                "contract_id"=> 1,
                "msa_id"=>null,
                "performed_by"=>2,
                "action"=>"edited"
            ],
            [
                "contract_id"=> 2,
                "msa_id"=>null,
                "performed_by"=>2,
                "action"=>"added"
            ],
            [
                "contract_id"=> null,
                "msa_id"=>1,
                "performed_by"=>1,
                "action"=>"edited"
            ],
            [
                "contract_id"=> null,
                "msa_id"=>2,
                "performed_by"=>2,
                "action"=>"added"
            ]
        ] ;
         $userNotificationDataArray=[
            [
                "log_id"=> 1,
                "sendto_id"=>1,
            ],
            [
                "log_id"=> 1,
                "sendto_id"=>2,
            ],
            [
                "log_id"=> 1,
                "sendto_id"=>3,
            ],
            [
                "log_id"=> 1,
                "sendto_id"=>4,
            ],
            [
                "log_id"=> 2,
                "sendto_id"=>2,
            ],
            [
                "log_id"=> 2,
                "sendto_id"=>3,
            ],
            [
                "log_id"=> 3,
                "sendto_id"=>1,
            ],
            [
                "log_id"=> 3,
                "sendto_id"=>2,
            ],
            [
                "log_id"=> 4,
                "sendto_id"=>1,
            ],
            [
                "log_id"=> 4,
                "sendto_id"=>2,
            ],
            [
                "log_id"=> 4,
                "sendto_id"=>4,
            ],
        ];
        foreach ($activityLogDataArray as $logData) {
            $logData = new ActivityLogs($logData);
            $logData->save();
        }
        foreach ($userNotificationDataArray as $notificationData) {
            $notificationData = new UserNotifications($notificationData);
            $notificationData->save();
        }
    }
}

