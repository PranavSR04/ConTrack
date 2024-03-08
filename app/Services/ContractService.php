<?php

namespace App\Services;
use App\ServiceInterfaces\ContractInterface;
use App\Models\Addendums;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class ContractService implements ContractInterface{
    public function getContractData(Request $request, $id=null)
    {
        if ($id != null) { //get individual contracts data if id is passed.
            try {
                $contractData = Contracts::find($id);
                if (!$contractData) {
                    return response()->json(['error' => 'Id not found in the database'], 404);
                } else {
                    // Check contract type
                    $contractType = $contractData->contract_type;
                    $singleContract = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                        ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                        ->where('contracts.id', '=', $id)
                        ->select('contracts.*', 'msas.client_name', 'users.user_name', 'msas.region')->get();
                    //get milestone based on contract type  
                    if ($contractType == 'TM') {
                        $milestones = TimeAndMaterialContracts::where('tm_contracts.contract_id', '=', $id)
                            ->select('*');
                    } elseif ($contractType == 'FF') {
                        $milestones = FixedFeeContracts::where('ff_contracts.contract_id', '=', $id)
                            ->select('*');
                    }
                    $data = $milestones->get();
                    //joining with contract data
                    $combinedData = $singleContract->map(function ($contract) use ($data) {
                        $contract['milestones'] = $data->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });

                    //get all addendums
                    $addendum = Addendums::where('contract_id', '=', $id)
                        ->select('*')
                        ->get();
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($addendum) {
                        $contract['addendum'] = $addendum->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });

                    //get all associated users
                    $associatedUsers = AssociatedUsers::join('users', 'associated_users.user_id', '=', 'users.id')
                        ->where('contract_id', '=', $id)
                        ->select('associated_users.id', 'contract_id', 'user_name', 'user_mail')
                        ->get();
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($associatedUsers) {
                        $contract['associated_users'] = $associatedUsers->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });
                    return response()->json(["data" => $combinedData]);
                }
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        try {
            //get data in request parameter
            $requestData = $request->all();
            $request->pagination ? $paginate = $request->pagination : $paginate = 10; //default pagination 10
            $querydata = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                ->select(
                    'contracts.id',
                    'msas.client_name',
                    'users.user_name',
                    'contracts.contract_type',
                    'contracts.date_of_signature',
                    'contracts.contract_ref_id',
                    'contracts.comments',
                    'contracts.start_date',
                    'contracts.end_date',
                    'du',
                    'estimated_amount',
                    'contract_doclink',
                    'contract_status'
                )
                ->where('contract_status', '!=', 'Expired');
            if (empty($requestData)) {
                return $querydata->paginate($paginate);
            } else {
                foreach ($requestData as $key => $value) {
                   
                    if(in_array($key, ['contract_ref_id','client_name','du','contract_type','msa_ref_id','contract_status'])){
                        $querydata->where($key, 'LIKE', '%' . $value . '%');
                    }
                    if($key =='sort_by'){
                        $querydata->orderBy($value, $request->sort_value);
                    }
                    if (in_array($key, ['start_date', 'end_date'])) {
                        $querydata->where('contracts.' . $key, 'LIKE', '%' . $value . '%');
                    }
                }
                if ($querydata->count() == 0) {
                    return response()->json(['error' => 'Data not found'], 404);
                }

                return $querydata->paginate($paginate);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}