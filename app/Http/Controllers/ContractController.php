<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class ContractController extends Controller
{
    public function insertContractsData()
    {
        $contractsDataArray = [
            [
                'contract_ref_id' => 'AGF7',
                'msa_id' => 2,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => " view document to see further milestone data",
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'du' => 'DU1',
                'estimated_amount' => 200000.00,
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'A166',
                'msa_id' => 2,
                'contract_added_by' => 1,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(11),
                'du' => 'DU1',
                'estimated_amount' => 250000,
                'contract_doclink' => "doc/link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'ABC1',
                'msa_id' => 2,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(3),
                'comments' => "High priority, complete on time",
                'start_date' => now(),
                'end_date' => now()->addMonths(10),
                'du' => 'DU1',
                'estimated_amount' => 800000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qx",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'A097',
                'msa_id' => 3,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Easy project work, needs to be done quickly",
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'du' => 'DU1',
                'estimated_amount' => 2500000,
                'contract_doclink' => "link.doc",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'A921',
                'msa_id' => 5,
                'contract_added_by' => 4,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Needs High priority",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 1200000,
                'contract_doclink' => "liiink.doc",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'AN21',
                'msa_id' => 2,
                'contract_added_by' => 1,
                'contract_type' => "T&M",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "File also available in sharepoint",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 2200000,
                'contract_doclink' => "liiink.doc",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'N621',
                'msa_id' => 5,
                'contract_added_by' => 1,
                'contract_type' => "T&M",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 2400000,
                'contract_doclink' => "liiink.doc",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'A091',
                'msa_id' => 2,
                'contract_added_by' => 1,
                'contract_type' => "T&M",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Updated contract on harleys",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 600000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qx",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'M921',
                'msa_id' => 4,
                'contract_added_by' => 4,
                'contract_type' => "T&M",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Contact me if it requires further change",
                'start_date' => now(),
                'end_date' => now()->addMonths(10),
                'du' => 'DU1',
                'estimated_amount' => 11200000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qx",
                'is_active' => false
            ],

        ];

        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
        }
        return response()->json(['Data inserted']);
    }
    public function getContractData(Request $request, $id=null)
    {
        
        if($id!=null){
            try {
            $contractData = Contracts::find($id);
            if(!$contractData){
                return response()->json(['error'=> 'Id not found in the database'],404); 
                }
            else {
                // Check contract type
                $contractType = $contractData->contract_type;
                $singleContract=Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                ->where('contracts.id','=',$id)
                ->select('contracts.*', 'msas.client_name', 'users.user_name')->get();
                if ($contractType == 'T&M') {
                    $milestones=TimeAndMaterialContracts::where('tm_contracts.contract_id','=',$id)
                    ->select('*');
                } elseif ($contractType == 'Fixed Fee') {
                    $milestones=FixedFeeContracts::where('ff_contracts.contract_id','=',$id)
                    ->select('*');
                }
                $data=$milestones->get();

               //joining with contract data
                $combinedData = $singleContract->map(function ($contract) use ($data) {
                    $contract['milestones'] = $data->where('contract_id', $contract['id'])->values()->all();
                    return $contract;
                });

                
                return response()->json($combinedData); 
            }
        }
        catch (Exception $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }
    } 
    
        try{
            //get data in request parameter
            $requestData = $request->all();
            $querydata=Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
            ->join('users', 'contracts.contract_added_by', '=', 'users.id')
            ->select('msas.client_name', 'users.user_name','contracts.*');
            if (empty($requestData)) {
                return $querydata->paginate('10');
            } else {
                foreach ($requestData as $key => $value) {
                   
                    if(in_array($key, ['contract_ref_id','client_name','du','contract_type','msa_ref_id','status'])){
                        $querydata->where($key, 'LIKE', '%' . $value . '%');
                    }
                    if($key =='sort_by'){
                        $querydata->orderBy($value, $request->sort_value);
                    }
                    if(in_array($key, ['start_date', 'end_date'])){
                        $querydata->where('contracts.'.$key, 'LIKE', '%' . $value . '%');
                    }
                    
            }
            if ($querydata->count() == 0) {
                return response()->json(['error' => 'Data not found'], 404);
            }  
            
                return $querydata->paginate('10');

        } 
    }
    catch (Exception $e) {
        return response()->json(['error'=> $e->getMessage()],500);
    }
}
}
