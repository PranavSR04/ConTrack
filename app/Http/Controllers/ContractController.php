<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function insertContractsData()
    {
        $contractsDataArray = [
            [
                'contract_ref_id' => 'GF7',
                'msa_ref_id' => 1,
                'added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'du' => 'DU1',
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => '166',
                'msa_ref_id' => 1,
                'added_by' => 1,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(11),
                'du' => 'DU1',
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'BC1',
                'msa_ref_id' => 1,
                'added_by' => 2,
                'contract_type' => "TandM",
                'date_of_signature' => now()->subMonths(3),
                'comments' => "High priority, complete on time",
                'start_date' => now(),
                'end_date' => now()->addMonths(10),
                'du' => 'DU1',
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => '3097',
                'msa_ref_id' => 3,
                'added_by' => 2,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Easy project work, needs to be done quickly",
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'du' => 'DU1',
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'A21',
                'msa_ref_id' => 1,
                'added_by' => 1,
                'contract_type' => "FF",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'contract_doclink' => "liiink.doc",
                'is_active' => false
            ],
            
        ];
        
        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
    }
}
public function getContractData(Request $request)
    {
        $individualContract = $request->all();
        $querydata=Contracts::join('msas', 'contracts.msa_ref_id', '=', 'msas.id')
        ->join('users', 'contracts.added_by', '=', 'users.id')
        ->select('contracts.*', 'msas.client_name', 'users.username');
        if (empty($individualContract)) {
            return $querydata->get();
        }
        else {  
            foreach ($individualContract as $key => $value) {
                if(in_array($key, ['id','name','date','contract_type','status'])){
                    $querydata->where('contracts.'.$key, $value);
                }
            return $querydata->get();
        }
        
    }
    }
}
