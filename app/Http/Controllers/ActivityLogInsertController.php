<?php

namespace App\Http\Controllers;
use App\Models\ActivityLogs;
use App\ServiceInterfaces\ActivityLogInsertInterface;
use Illuminate\Http\Request;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;

class ActivityLogInsertController extends Controller
{
    private $activityLogInsertService;
    public function __construct(ActivityLogInsertInterface $activityLogInsertService)
    {
        $this->activityLogInsertService = $activityLogInsertService;
    }
    public function addToActivityLog($contract_id,$msa_id,$performed_by,$action){

        return $this->activityLogInsertService->addToActivityLog($contract_id,$msa_id,$performed_by,$action);
       
    }
}

