<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface ActivityLogInsertInterface
{
    public function addToActivityLog($contract_id,$msa_id,$performed_by,$action);
    
}