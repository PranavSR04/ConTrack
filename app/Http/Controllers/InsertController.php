<?php

namespace App\Http\Controllers;
use App\Models\ActivityLogs;
use Illuminate\Http\Request;

class InsertController extends Controller
{
    public function addToActivityLog($contract_id,$msa_id,$performed_by,$action){
        ActivityLogs::create([
            'contract_id' => $contract_id,
            'msa_id' => $msa_id,
            'performed_by' => $performed_by,
            'action' => $action,
        ]);
       
    }
}

