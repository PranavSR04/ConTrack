<?php
namespace App\Http\Controllers;

use App\Models\Addendums;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function insertContractsData()
    {
        $contractsDataArray = [
            [
                'contract_ref_id' => 'AGF7',
                'msa_id' => 1,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => " view document to see further milestone data",
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'du' => 'DU1',
                'estimated_amount' => 200000.00,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qx",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'A166',
                'msa_id' => 1,
                'contract_added_by' => 1,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(11),
                'du' => 'DU1',
                'estimated_amount' => 250000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=sdfsfd1&web=1&e=pNA6Qx",
                'is_active' => true
            ],
            [
                'contract_ref_id' => 'ABC1',
                'msa_id' => 1,
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
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=adas1&web=1&e=pNA6Qx",
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
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qxasdasd",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'AN21',
                'msa_id' => 2,
                'contract_added_by' => 1,
                'contract_type' => "TM",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "File also available in sharepoint",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 2200000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qxsda",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'N621',
                'msa_id' => 5,
                'contract_added_by' => 1,
                
                'contract_type' => "TM",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now(),
                'end_date' => now()->addMonths(9),
                'du' => 'DU1',
                'estimated_amount' => 2400000,
                'contract_doclink' => "https://experiontechnologies-my.sharepoint.com/:x:/r/personal/pranav_sr_experionglobal_com/Documents/Contrack%20DB%20Design.xlsx?d=wf1de9a65fe984daba803e1e0edb882ac&csf=1&web=1&e=pNA6Qxasdasdw",
                'is_active' => false
            ],
            [
                'contract_ref_id' => 'A091',
                'msa_id' => 1,
                'contract_added_by' => 1,
                'contract_type' => "TM",
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
                'contract_type' => "TM",
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
        //insert each set of data through looping
        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
        }
        return response()->json(['Data inserted']);
    }
    public function getContractData(Request $request, $id=null)
    {
        if($id!=null){ //get individual contracts data if id is passed.
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
                //get milestone based on contract type  
                if ($contractType == 'TM') {
                    $milestones=TimeAndMaterialContracts::where('tm_contracts.contract_id','=',$id)
                    ->select('*');
                } elseif ($contractType == 'FF') {
                    $milestones=FixedFeeContracts::where('ff_contracts.contract_id','=',$id)
                    ->select('*');
                }
                $data=$milestones->get();
               //joining with contract data
                $combinedData = $singleContract->map(function ($contract) use ($data) {
                    $contract['milestones'] = $data->where('contract_id', $contract['id'])->values()->all();
                    return $contract;
                }); 

                //get all addendums
                $addendum=Addendums::where('contract_id','=',$id)
                ->select('*')
                ->get();
                if(!$addendum->count() == 0){
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($addendum) {
                        $contract['addendum'] = $addendum->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    }); 
                }
              //get all associated users
              $associatedUsers=AssociatedUsers::where('contract_id','=',$id)
                ->select('*')
                ->get();
                if(!$associatedUsers->count() == 0){
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($associatedUsers) {
                        $contract['associated_users'] = $associatedUsers->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    }); 
                }
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
            $request->pagination?$paginate = $request->pagination:$paginate=10; //default pagination 10
            $querydata=Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
            ->join('users', 'contracts.contract_added_by', '=', 'users.id')
            ->select('msas.client_name', 'users.user_name','contracts.contract_type','contracts.date_of_signature',
            'contracts.contract_ref_id','contracts.comments','contracts.start_date','contracts.end_date','du','estimated_amount','contract_doclink','contract_status')
            ->where('contract_status', '!=', 'Expired' );
            if (empty($requestData)) {
                return $querydata->paginate($paginate);
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
            
                return $querydata->paginate($paginate);
        } 
    }
    catch (Exception $e) {
        return response()->json(['error'=> $e->getMessage()],500);
    }
}
    public function addContract(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'msa_id' => 'required|exists:msas,id',
            'contract_ref_id' => 'required|string|max:25',
            'contract_type' => 'required|string|max:25',
            'start_date' => 'required|date|before:end_date|after:date_of_signature',
            'end_date' => 'required|date|after:start_date',
            'date_of_signature' => 'required|date',
            'du' => 'required|string',
            'estimated_amount' => 'required|numeric|min:0',
            'comments' => 'string',
            'contract_doclink' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $validated = $validator->validated();

        try {
            $totalAmount = 0;
            $totalPercentage = 0;

            if ($request->contract_type === 'FF') {
                if (!empty($request->ff_milestones)) {
                    foreach ($request->ff_milestones as $milestone) {
                        $totalPercentage += $milestone['percentage'];
                        $totalAmount += $milestone['amount'];
                    }
                } else {
                    throw new Exception('No milestones provided for Fixed Fee contract.');
                }
            } else {
                if (!empty($request->tm_milestones)) {
                    foreach ($request->tm_milestones as $milestone) {
                        $totalAmount += $milestone['amount'];
                    }
                } else {
                    throw new Exception('No milestones provided for Time and Material contract.');
                }
            }

            if ($request->contract_type === 'FF' && ($totalPercentage !== 100 || $totalAmount !== $request->estimated_amount)) {
                return response()->json(['error' => 'Invalid milestones for Fixed Fee contract.'], 400);
            }

            if ($request->contract_type === 'TM' && $totalAmount !== $request->estimated_amount) {
                return response()->json(['error' => 'Invalid milestones for Time and Material contract.'], 400);
            }

            $contract = Contracts::create([
                'msa_id' => $request->msa_id,
                'contract_added_by' => $request->contract_added_by,
                'contract_ref_id' => $request->contract_ref_id,
                'contract_type' => $request->contract_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'date_of_signature' => $request->date_of_signature,
                'du' => $request->du,
                'is_active' => true,
                'estimated_amount' => $request->estimated_amount,
                'comments' => $request->comments,
                'contract_doclink' => $request->contract_doclink,
            ]);
            $contractId = $contract->id;
            // return response ()->json([$request->assoc[0]]);
            if (!empty($request->assoc)) {
                foreach ($request->assoc as $users) {
                    // return response()->json([$users['user_id']]);
                    AssociatedUsers::create([
                        'contract_id' => $contractId,
                        'user_id' => $users['user_id'],
                    ]);
                }
            }

            if ($request->contract_type === 'FF') {
                foreach ($request->ff_milestones as $milestone) {
                    FixedFeeContracts::create([
                        'contract_id' => $contractId,
                        'milestone_desc' => $milestone['milestone_desc'],
                        'milestone_enddate' => $milestone['milestone_enddate'],
                        'percentage' => $milestone['percentage'],
                        'amount' => $milestone['amount'],
                    ]);
                }
            } else {
                foreach ($request->tm_milestones as $milestone) {
                    TimeAndMaterialContracts::create([
                        'contract_id' => $contractId,
                        'milestone_desc' => $milestone['milestone_desc'],
                        'milestone_enddate' => $milestone['milestone_enddate'],
                        'amount' => $milestone['amount'],
                    ]);
                }
            }

            return response()->json(['message' => 'Contract created successfully', 'contract' => $contractId], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create contract', 'message' => $e->getMessage()], 500);
        }
    }


}
;