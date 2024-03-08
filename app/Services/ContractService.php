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

    /**
     * Function to update a contract.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing updated contract data.
     * @param int $contractId The ID of the contract to be updated.
     * @return \Illuminate\Http\JsonResponse|string JSON response indicating the status of the update or an error message.
     */
    public function updateContractData(Request $request, $contractId)
    {
        try {
            $contract = Contracts::find($contractId);
            // return response()->json([$contract]);
            if (!$contract) {
                return response()->json(['error' => 'Contract not found'], 404);
            }

            // Checking whether contract needed to be closed
            if ($request->contract_status === "Closed") {
                $result = Contracts::where('id', $contractId)->update(['contract_status', "Closed"]);
                return response()->json(['message' => 'Contract Closed']);
            }

            $contract_type = Contracts::where('id', $contractId)->value('contract_type');
            // return response()->json([$contract_type]);
            if ($contract_type === 'FF') {
                // Parsing string data into array    
                $decodedMilestones = json_decode($request->milestones, true);
                $decodedAssociatedUsers = json_decode($request->associated_users, true);

                // Validate the incoming request data
                $validator_ff = Validator::make(['milestones' => $decodedMilestones] + ['associated_users' => $decodedAssociatedUsers] + $request->all(), [
                    'msa_id' => 'required|numeric',
                    'contract_added_by' => 'required|numeric',
                    'client_name' => 'string|min:5|max:100',
                    'region' => 'string|max:100',
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date',
                    'date_of_signature' => 'date|before:start_date',
                    'contract_status' => 'required|string',
                    'du' => 'required|string',
                    'comments' => 'string',
                    'contract_doclink' => 'string',
                    'file' => 'file',
                    'estimated_amount' => 'required|numeric',
                    "milestones" => ['required', 'array'],
                    'milestones.*.milestone_desc' => 'required|string',
                    'milestones.*.milestone_enddate' => 'required|date',
                    'milestones.*.percentage' => 'required|numeric',
                    'milestones.*.amount' => 'required|numeric',
                    'addendum_file' => [
                        'sometimes',
                        'nullable',
                        function ($attribute, $value, $fail) {
                            // Check if the value is a string or a file
                            if (!is_string($value) && !is_a($value, \Illuminate\Http\UploadedFile::class)) {
                                $fail($attribute . ' must be a valid file or a string.');
                            }
                        },
                    ],
                    'addendum_doclink' => 'string',
                    'associated_users' => 'array',
                    'exists:users,id',
                    'associated_users.*.user_id' => 'required|numeric',
                ]);
                if ($validator_ff->fails()) {
                    return response()->json(['error' => $validator_ff->errors()], 422);
                }

                $validated_ff = $validator_ff->validated();

                $googleDrive = new GoogleDriveService();

                if ($request->contract_status !== "Closed" || $request->contract_status !== "closed" || $request->contract_status !== "CLOSED") {
                    // Checking only to update the data of contracts which are not having status as closed
                    $fileLink = $googleDrive->store($request);
                    if ($fileLink) {
                        // If contract file is uploaded it returns a link 
                        $contractUpdateData = [
                            'msa_id' => $validated_ff['msa_id'],
                            'contract_added_by' => $validated_ff['contract_added_by'],
                            'start_date' => $validated_ff['start_date'],
                            'end_date' => $validated_ff['end_date'],
                            'date_of_signature' => $validated_ff['date_of_signature'],
                            'du' => $validated_ff['du'],
                            'contract_status' => $validated_ff['contract_status'],
                            'comments' => $validated_ff['comments'],
                            'contract_doclink' => $fileLink,
                            'estimated_amount' => $validated_ff['estimated_amount'],
                        ];
                    } else {
                        $contractUpdateData = [
                            'msa_id' => $validated_ff['msa_id'],
                            'contract_added_by' => $validated_ff['contract_added_by'],
                            'start_date' => $validated_ff['start_date'],
                            'end_date' => $validated_ff['end_date'],
                            'date_of_signature' => $validated_ff['date_of_signature'],
                            'du' => $validated_ff['du'],
                            'contract_status' => $validated_ff['contract_status'],
                            'comments' => $validated_ff['comments'],
                            'contract_doclink' => $validated_ff['contract_doclink'],
                            'estimated_amount' => $validated_ff['estimated_amount'],
                        ];
                    }

                    $milestonesUpdateData = [];
                    foreach ($decodedMilestones as $milestone) {
                        $milestonesUpdateData[] = [
                            'milestone_desc' => $milestone['milestone_desc'],
                            'milestone_enddate' => $milestone['milestone_enddate'],
                            'percentage' => $milestone['percentage'],
                            'amount' => $milestone['amount'],
                        ];
                    }

                    $sumPercentages = array_sum(array_column($milestonesUpdateData, 'percentage'));
                    $sumAmounts = array_sum(array_column($milestonesUpdateData, 'amount'));

                    if ($sumPercentages == 100) {
                        if ($sumAmounts == $validated_ff['estimated_amount']) {
                            // Insertion starts only after all validation finishes
                            $contractResult = Contracts::where('id', $contractId)->update($contractUpdateData);

                            // For enterting data into Associated Users table
                            if (!empty($request->associated_users)) {
                                foreach ($decodedAssociatedUsers as $user) {
                                    $userId = $user['user_id'];

                                    AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                                    $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                                }
                            }

                            // For enterting data into Fixed fee table
                            foreach ($milestonesUpdateData as $milestoneData) {
                                $ffResult = FixedFeeContracts::updateOrCreate(
                                    [
                                        'contract_id' => $contractId,
                                        'milestone_desc' => $milestoneData['milestone_desc'],
                                    ],
                                    [
                                        'milestone_enddate' => $milestoneData['milestone_enddate'],
                                        'percentage' => $milestoneData['percentage'],
                                        'amount' => $milestoneData['amount'],
                                    ]
                                );
                            }

                            // Insertion into addendum table and addendum uploaded to drive
                            if (isset($validated_ff['addendum_file']) && $validated_ff['addendum_file'] !== '') {
                                $addendum = new AddendumService();
                                $addendum->store($request, $contractId);
                            }


                            return response()->json([
                                "message" => "Contract edited successfully",
                                "data" => [
                                    'contract_result' => $contractResult,
                                    'milestones_result' => $ffResult,
                                    'associatedusers_result' => $associated_users,
                                ]
                            ]);
                        } else {
                            return response()->json(['error' => "Sum of amount not equal to estimated amount"], 422);
                        }
                    } else {
                        return response()->json(['error' => "Sum of percentage not equal to 100"], 422);
                    }

                } else {
                    return response()->json(['error' => "Contract was closed"], 422);
                }


            } else if ($contract_type === 'TM') {
                // Parsing string data into array    
                $decodedMilestones = json_decode($request->milestones, true);
                $decodedAssociatedUsers = json_decode($request->associated_users, true);

                // Validate the incoming request data
                $validator_tm = Validator::make(['milestones' => $decodedMilestones] + ['associated_users' => $decodedAssociatedUsers] + $request->all(), [
                    'msa_id' => 'required|numeric',
                    'contract_added_by' => 'required|numeric',
                    'client_name' => 'string|min:5|max:100',
                    'region' => 'string|max:100',
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date',
                    'date_of_signature' => 'date|before:start_date',
                    'contract_status' => 'required|string',
                    'du' => 'required|string',
                    'comments' => 'string',
                    'contract_doclink' => 'string',
                    'file' => 'file',
                    'estimated_amount' => 'required|numeric',
                    "milestones" => ['required', 'array'],
                    'milestones.*.milestone_desc' => 'required|string',
                    'milestones.*.milestone_enddate' => 'required|date',
                    'milestones.*.amount' => 'required|numeric',
                    'associated_users' => 'array',
                    'exists:users,id',
                    'associated_users.*.user_id' => 'required|numeric',
                    'addendum_file' => [
                        'sometimes',
                        'nullable',
                        function ($attribute, $value, $fail) {
                            // Check if the value is a string or a file
                            if (!is_string($value) && !is_a($value, \Illuminate\Http\UploadedFile::class)) {
                                $fail($attribute . ' must be a valid file or a string.');
                            }
                        },
                    ],
                    'addendum_doclink' => 'string',
                ]);


                if ($validator_tm->fails()) {
                    return response()->json(['error' => $validator_tm->errors()], 422);
                }

                $validated_tm = $validator_tm->validated();

                $googleDrive = new GoogleDriveService();

                if ($validated_tm['contract_status'] !== "Closed" || $validated_tm['contract_status'] !== "closed" || $validated_tm['contract_status'] !== "CLOSED") {
                    // Checking only to update the data of contracts which are not having status as closed
                    $fileLink = $googleDrive->store($request);
                    if ($fileLink) {
                        $contractUpdateData = [
                            'msa_id' => $validated_tm['msa_id'],
                            'contract_added_by' => $validated_tm['contract_added_by'],
                            'start_date' => $validated_tm['start_date'],
                            'end_date' => $validated_tm['end_date'],
                            'date_of_signature' => $validated_tm['date_of_signature'],
                            'du' => $validated_tm['du'],
                            'contract_status' => $validated_tm['contract_status'],
                            'comments' => $validated_tm['comments'],
                            'contract_doclink' => $fileLink,
                            'estimated_amount' => $validated_tm['estimated_amount'],
                        ];
                    } else {
                        $contractUpdateData = [
                            'msa_id' => $validated_tm['msa_id'],
                            'start_date' => $validated_tm['start_date'],
                            'contract_added_by' => $validated_tm['contract_added_by'],
                            'end_date' => $validated_tm['end_date'],
                            'date_of_signature' => $validated_tm['date_of_signature'],
                            'du' => $validated_tm['du'],
                            'contract_status' => $validated_tm['contract_status'],
                            'comments' => $validated_tm['comments'],
                            'estimated_amount' => $validated_tm['estimated_amount'],
                            'contract_doclink' => $validated_tm['contract_doclink'],
                        ];
                    }

                    $milestonesUpdateData = [];
                    foreach ($decodedMilestones as $milestone) {
                        $milestonesUpdateData[] = [
                            'milestone_desc' => $milestone['milestone_desc'],
                            'milestone_enddate' => $milestone['milestone_enddate'],
                            'percentage' => $milestone['percentage'],
                            'amount' => $milestone['amount'],
                        ];
                    }

                    $sumAmounts = array_sum(array_column($milestonesUpdateData, 'amount'));


                    if ($sumAmounts == $validated_tm['estimated_amount']) {
                        $contractResult = Contracts::where('id', $contractId)->update($contractUpdateData);

                        // For enterting data into Associated Users table
                        if (!empty($request->associated_users)) {
                            foreach ($decodedAssociatedUsers as $user) {
                                $userId = $user['user_id'];

                                AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                                $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                            }
                        }

                        foreach ($milestonesUpdateData as $milestoneData) {
                            $tmResult = TimeAndMaterialContracts::updateOrCreate(
                                [
                                    'contract_id' => $contractId,
                                    'milestone_desc' => $milestoneData['milestone_desc'],
                                ],
                                [
                                    'milestone_enddate' => $milestoneData['milestone_enddate'],
                                    'amount' => $milestoneData['amount'],
                                ]
                            );
                        }


                        // Insertion into addendum table and addendum uploaded to drive
                        if (isset($validated_tm['addendum_file']) && $validated_tm['addendum_file'] !== '') {
                            $addendum = new AddendumService();
                            $addendum->store($request, $contractId);
                        }



                        return response()->json([
                            "message" => "Contract edited successfully",
                            "data" => [
                                'contract_result' => $contractResult,
                                'milestones_result' => $tmResult,
                                'associatedusers_result' => $associated_users,
                            ]
                        ]);
                    } else {
                        return response()->json(['error' => "Sum of amount not equal to estimated amount"], 422);
                    }
                } else {
                    return response()->json(['error' => "Contract was closed"], 422);
                }


            } else {
                return response()->json(["error" => "Invalid Contract Type"], 422);
            }
        } catch (Exception $e) {
            // return response()->json(['error' => "Failed to edit contract"], 500);
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
}