<?php

namespace App\Services;

use App\Http\Controllers\GoogleDriveController;
use App\ServiceInterfaces\ContractInterface;
use App\Models\Addendums;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class ContractService implements ContractInterface
{
    public function getContractData(Request $request, $id = null)
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

                    if (in_array($key, ['contract_ref_id', 'client_name', 'du', 'contract_type', 'msa_ref_id', 'contract_status'])) {
                        $querydata->where($key, 'LIKE', '%' . $value . '%');
                    }
                    if ($key == 'sort_by') {
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
            // 'contract_doclink' => 'string',
            'file' => 'file',
            'associated_users' => ['array', 'exists:users,id'],
            'associated_users.*.user_id' => 'required|numeric',
            'milestone' => 'required|array',

        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $validated = $validator->validated();

        try {
            $totalAmount = 0;
            $totalPercentage = 0;
        
            $decodedMilestones = $request->milestone;
            var_dump($decodedMilestones);
            if ($decodedMilestones === null) {
                return response()->json(['error' => 'Invalid JSON format for milestones'], 422);
            }
            // var_dump($decodedMilestones);

            foreach ($decodedMilestones as $milestone) {
                // Access each milestone's properties
                $milestone_desc = $milestone['milestone_desc'];
                $milestone_enddate = $milestone['milestone_enddate'];
                $percentage = $milestone['percentage'];
                $amount = $milestone['amount'];

                
            }

            foreach ($decodedMilestones as $milestone) {
                var_dump($milestone);
            }
            if ($request->contract_type === 'FF') {
                if (!empty($request->milestone)) {
                    // $decodedMilestones = $request->milestone;


                    try {
                        // foreach ($request->milestone as $milestone) {
                        //     $totalPercentage += $milestone['percentage'];
                        //     $totalAmount += $milestone['amount'];
                        // }
                        if (!is_array($request->milestone)) {
                            // Loop through array of milestones
                            foreach ($request->milestone as $milestone) {
                                $totalPercentage += $milestone['percentage'];
                                $totalAmount += $milestone['amount'];
                            }
                        } else {
                            // Handle the case when $request->milestone is a string
                            // For example, if it's a JSON string, you could decode it
                            $milestones = json_decode($request->milestone, true);
                            if ($milestones) {
                                foreach ($milestones as $milestone) {
                                    $totalPercentage += $milestone['percentage'];
                                    $totalAmount += $milestone['amount'];
                                }
                            }
                        }
                    } catch (Exception $e) {
                        throw new Exception("Error Processing Request", 1);

                    }
                } else {
                    return response()->json(['error' => 'No milestones provided for Fixed fee contract.'], 422);
                }
            } else {


                if (!empty($request->milestone)) {
                    if (is_array($request->milestone)) {
                        foreach ($request->milestone as $milestone) {
                            $totalAmount += $milestone['amount'];
                        }
                    } else {
                        foreach (json_decode($request->milestone, true) as $milestone) {
                            // $totalPercentage += $milestone['percentage'];

                            $totalAmount += $milestone['amount'];
                        }
                    }
                } else {
                    return response()->json(['error' => 'No milestones provided for Time and Material contract.'], 422);

                }
            }

            if ($request->contract_type === 'FF' && ($totalPercentage !== 100 || $totalAmount !== $request->estimated_amount)) {
                return response()->json(['error' => 'Invalid milestones for Fixed Fee contract.'], 422);
            }

            if ($request->contract_type === 'TM' && $totalAmount !== (int) $request->estimated_amount) {
                return response()->json(['error' => 'Invalid milestones for Time and Material contract.'], 422);
            }
            $googleDrive = new GoogleDriveController();


            $fileLink = $googleDrive->store($request);
            if ($fileLink) {
                $contract = Contracts::create([
                    'msa_id' => $request->msa_id,
                    'contract_added_by' => $request->contract_added_by,
                    'contract_ref_id' => $request->contract_ref_id,
                    'contract_type' => $request->contract_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'date_of_signature' => $request->date_of_signature,
                    'du' => $request->du,
                    'contract_status' => "Active",
                    'estimated_amount' => $request->estimated_amount,
                    'comments' => $request->comments,
                    'contract_doclink' => $fileLink,

                ]);
            } else {
                $contract = Contracts::create([
                    'msa_id' => $request->msa_id,
                    'contract_added_by' => $request->contract_added_by,
                    'contract_ref_id' => $request->contract_ref_id,
                    'contract_type' => $request->contract_type,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'date_of_signature' => $request->date_of_signature,
                    'du' => $request->du,
                    'contract_status' => "Active",
                    'estimated_amount' => $request->estimated_amount,
                    'comments' => $request->comments,
                    'contract_doclink' => $fileLink,
                ]);
            }
            $contractId = $contract->id;
            // return response ()->json([$request->assoc[0]]);

            if (!empty($request->assoc)) {
                if (!is_array($request->assoc)) {
                    foreach (json_decode($request->assoc, true) as $users) {
                        // return response()->json([$users['user_id']]);
                        $assoc_users = AssociatedUsers::create([
                            'contract_id' => $contractId,
                            'user_id' => $users['user_id'],
                        ]);
                    }
                }
            }

            if ($request->contract_type === 'FF') {
                try {
                    if (!is_array($request->milestone)) {
                        foreach (json_decode($request->milestone, true) as $milestone) {
                            $ffresult = FixedFeeContracts::create([
                                'contract_id' => $contractId,
                                'milestone_desc' => $milestone['milestone_desc'],
                                'milestone_enddate' => $milestone['milestone_enddate'],
                                'percentage' => $milestone['percentage'],
                                'amount' => $milestone['amount'],
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    var_dump($e);
                    throw new Exception('Error is milestone not array');
                }
                return response()->json([
                    'message' => 'Contract created successfully',
                    'data' =>
                        ['contract_result' => $contract, 'associated_users_result' => $assoc_users, "milestone_result" => $ffresult]
                ], 201);
            } else {
                if (!is_array($request->milestone)) {
                    foreach (json_decode($request->milestone, true) as $milestone) {
                        $tmresult = TimeAndMaterialContracts::create([
                            'contract_id' => $contractId,
                            'milestone_desc' => $milestone['milestone_desc'],
                            'milestone_enddate' => $milestone['milestone_enddate'],
                            'amount' => $milestone['amount'],
                        ]);
                    }
                    return response()->json([
                        'message' => 'Contract created successfully',
                        'data' =>
                            ['contract_result' => $contract, 'associated_users_result' => $assoc_users, "milestone_result" => $tmresult]
                    ], 201);
                }
            }


        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                return response()->json(['error' => 'User not valid'], 500);

            } else {
                return response()->json(['error' => 'Failed to create contract', 'message' => $e->getMessage()], 500);

            }

        }
    }
}