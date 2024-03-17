<?php

namespace App\Services;

use App\Http\Controllers\ActivityLogInsertController;
use App\ServiceInterfaces\ContractInterface;
use App\Models\Addendums;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                ->orderBy('contracts.updated_at', 'desc');
            if (empty ($requestData)) {
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
                if ($request->status) {
                    $querydata->where('contract_status', '=', $request->status);
                } else {
                    $querydata->where('contract_status', '!=', 'Expired');
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
        // return response()->json($request->all());
        try {
            $contract = Contracts::find($contractId);
            // return response()->json([$contract]);
            if (!$contract) {
                return response()->json(['error' => 'Contract not found'], 404);
            }

            // Checking whether contract needed to be closed
            if ($request->contract_status === "Closed") {
                $result = Contracts::where('id', $contractId)->update(['contract_status' => "Closed"]);
                return response()->json(['message' => 'Contract Closed']);
            }

            $contract_type = Contracts::where('id', $contractId)->value('contract_type');
            // return response()->json([$contract_type]);
            if ($contract_type === 'FF') {
                // Parsing string data into array   
                // $decodedMilestones = $request->milestones;
                $decodedMilestones = $request->milestones;
                if (!is_array(($request->milestones))) {
                    $decodedMilestones = json_decode($request->milestones, true);
                }

                $decodedAssociatedUsers = $request->associated_users;
                if (!is_array(($request->associated_users))) {
                    $decodedAssociatedUsers = json_decode($request->associated_users, true);
                }

                // Validate the incoming request data
                $validator_ff = Validator::make(['milestones' => $decodedMilestones] + $request->all(), [
                    // $validator_ff = Validator::make(['milestones' => $decodedMilestones] + ['associated_users' => $decodedAssociatedUsers] + $request->all(), [
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
                    // 'associated_users' => 'array',
                    // 'exists:users,id',
                    // 'associated_users.*.user_id' => 'required|numeric',
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
                            'contract_ref_id' => $request->contract_ref_id,
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
                            'contract_ref_id' => $request->contract_ref_id,
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
                            Contracts::where('id', $contractId)->update($contractUpdateData);
                            $contractResult = Contracts::where('id', $contractId)->get();

                            // For enterting data into Associated Users table
                            // if (!empty($request->associated_users)) {
                            //     foreach ($decodedAssociatedUsers as $user) {
                            //         $userId = $user['user_id'];

                            //         AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                            //         $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                            //     }
                            // }

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
                            if (isset ($validated_ff['addendum_file']) && $validated_ff['addendum_file'] !== '') {
                                $addendum = new AddendumService();
                                $addendum->store($request, $contractId);
                            }

                            $action = "Edited";
                            $activityLogInsertService = new ActivityLogInsertService();
                            $insertController = new ActivityLogInsertController($activityLogInsertService);
                            $insertController->addToActivityLog($contractId, $request->msa_id, $request->contract_added_by, $action);


                            return response()->json([
                                "message" => "Contract edited successfully",
                                "data" => [
                                    'contract_result' => $contractResult,
                                    'milestones_result' => $ffResult,
                                    // 'associatedusers_result' => $associated_users,
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
                // $decodedMilestones = json_decode($request->milestones, true);
                // $decodedAssociatedUsers = json_decode($request->associated_users, true);
                // Parsing string data into array   
                $decodedMilestones = $request->milestones;
                if (!is_array(($request->milestones))) {
                    $decodedMilestones = json_decode($request->milestones, true);
                }

                $decodedAssociatedUsers = $request->associated_users;
                if (!is_array(($request->associated_users))) {
                    $decodedAssociatedUsers = json_decode($request->associated_users, true);
                }

                // Validate the incoming request data
                $validator_tm = Validator::make(['milestones' => $decodedMilestones] + $request->all(), [
                    // $validator_tm = Validator::make(['milestones' => $decodedMilestones] + ['associated_users' => $decodedAssociatedUsers] + $request->all(), [
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
                    // 'associated_users' => 'array','exists:users,id',
                    // 'associated_users.*.user_id' => 'required|numeric',
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
                            'contract_ref_id' => $request->contract_ref_id,
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
                            'contract_ref_id' => $request->contract_ref_id,
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
                            'amount' => $milestone['amount'],
                        ];
                    }

                    $sumAmounts = array_sum(array_column($milestonesUpdateData, 'amount'));


                    if ($sumAmounts == $validated_tm['estimated_amount']) {
                        Contracts::where('id', $contractId)->update($contractUpdateData);
                        $contractResult = Contracts::where('id', $contractId)->get();

                        // For enterting data into Associated Users table
                        // if (!empty ($request->associated_users)) {
                        //     foreach ($decodedAssociatedUsers as $user) {
                        //         $userId = $user['user_id'];

                        //         AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                        //         $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                        //     }
                        // }

                        foreach ($milestonesUpdateData as $milestoneData) {
                            $tmResult = TimeAndMaterialContracts::updateOrCreate(
                                [
                                    'contract_id' => $contractId,
                                    'milestone_desc' => $milestoneData['milestone_desc'],
                                    'milestone_enddate' => $milestoneData['milestone_enddate'],
                                    'amount' => $milestoneData['amount'],
                                ]
                            );
                        }

                        // Insertion into addendum table and addendum uploaded to drive
                        if (isset ($validated_tm['addendum_file']) && $validated_tm['addendum_file'] !== '') {
                            $addendum = new AddendumService();
                            $addendum->store($request, $contractId);
                        }

                        // $action = "Edited";
                        // $activityLogInsertService = new ActivityLogInsertService();
                        // $insertController = new ActivityLogInsertController($activityLogInsertService);
                        // $insertController->addToActivityLog($contractId, $request->msa_id, $request->contract_added_by, $action);

                        return response()->json([
                            "message" => "Contract edited successfully",
                            "data" => [
                                'contract_result' => $contractResult,
                                'milestones_result' => $tmResult,
                                // 'associatedusers_result' => $associated_users,
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

        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $validated = $validator->validated();

        try {
            $totalAmount = 0;
            $totalPercentage = 0;
            $decodedMilestones = $request->milestone;
            if (!is_array(($request->milestone))) {
                $decodedMilestones = json_decode($request->milestone, true);
            }

            if ($decodedMilestones === null && json_last_error() !== JSON_ERROR_NONE) {
                // Handle decoding error
                return response()->json(['error' => 'Invalid JSON format for milestones'], 422, ["content-type" => "application/json"]);
            }

            if ($request->contract_type === 'FF') {

                if (!empty ($request->milestone)) {
                    try {
                        if (is_array($request->milestone)) {
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


                if (!empty ($request->milestone)) {
                    if (is_array($request->milestone)) {
                        foreach ($request->milestone as $milestone) {
                            $totalAmount += $milestone['amount'];
                        }
                    } else {
                        foreach (json_decode($request->milestone, true) as $milestone) {
                            $totalAmount += $milestone['amount'];
                        }
                    }
                } else {
                    return response()->json(['error' => 'No milestones provided for Time and Material contract.'], 422);

                }
            }

            if ($request->contract_type === 'FF' && ($totalPercentage !== 100 || floatval($totalAmount) !== floatval($request->estimated_amount))) {
                return response()->json(['error in milestone amount calculation' => 'Invalid milestones for Fixed Fee contract.'], 422);
            }

            if ($request->contract_type === 'TM' && $totalAmount !== (int) $request->estimated_amount) {
                return response()->json(['error' => 'Invalid milestones for Time and Material contract.'], 422);
            }
            $googleDrive = new GoogleDriveService();


            $fileLink = $googleDrive->store($request);
            if ($fileLink) {
                $contract = Contracts::create([
                    'msa_id' => $request->msa_id,
                    'contract_added_by' => $request->contract_added_by,
                    'contract_ref_id' => $request->contract_ref_id,
                    'contract_type' => $request->contract_type,
                    'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                    'date_of_signature' => Carbon::parse($request->date_of_signature)->format('Y-m-d'),
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

            $action = "Added";
            $activityLogInsertService = new ActivityLogInsertService();
            $insertController = new ActivityLogInsertController($activityLogInsertService);
            $insertController->addToActivityLog($contractId, $request->msa_id, $request->contract_added_by, $action);

            if (!empty ($request->assoc_users)) {
                if (!is_array($request->assoc_users)) {
                    foreach (json_decode($request->assoc_users, true) as $users) {
                        $assoc_users = AssociatedUsers::create([
                            'contract_id' => $contractId,
                            'user_id' => $users['user_id'],
                        ]);
                    }
                }
            }

            if ($request->contract_type === 'FF') {
                try {
                    if (is_array($request->milestone) && !empty ($request->milestone)) {
                        try {
                            foreach ($request->milestone as $milestone) {
                                var_dump($request->milestone);
                                $ffresult = FixedFeeContracts::create([
                                    'contract_id' => $contractId,
                                    'milestone_desc' => $milestone['milestones'],
                                    // 'milestone_enddate' => isset($milestone['milestone_enddate']) && !empty($milestone['milestone_enddate'])
                                    //     ? Carbon::parse($milestone['milestone_enddate'])->format('Y-m-d')
                                    //     : null,
                                    'milestone_enddate' => Carbon::parse($milestone['expectedCompletionDate'])->format('Y-m-d'),
                                    'percentage' => $milestone['percentage'],
                                    'amount' => $milestone['amount'],
                                ]);
                            }
                        } catch (Exception $e) {
                            return response()->json(['error' => 'Failed', 'message' => $e->getMessage()], 500);

                        }
                    }
                } catch (Exception $e) {
                    var_dump($e);
                    throw new Exception('Error is milestone not array');
                }
                return response()->json([
                    'message' => 'Contract created successfully',
                    'data' =>
                        ['contract_result' => $contract, 'associated_users_result' => !empty ($assoc_users) ? $assoc_users : "Nil", "milestone_result" => $ffresult]
                ], 201);
            } else if ($request->contract_type === 'TM') {
                if (is_array($request->milestone) && !empty ($request->milestone)) {
                    foreach ($request->milestone as $milestone) {
                        $tmresult = TimeAndMaterialContracts::create([
                            'contract_id' => $contractId,
                            'milestone_desc' => $milestone['milestones'],
                            'milestone_enddate' => Carbon::parse($milestone['expectedCompletionDate'])->format('Y-m-d'),
                            'amount' => $milestone['amount'],
                        ]);

                    }
                    return response()->json([
                        'message' => 'Contract created successfully',
                        'data' =>
                            ['contract_result' => $contract, !empty ($assoc_users) ? $assoc_users : "Nil", "milestone_result" => $tmresult]
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

    public function getDuCount(Request $request)
    {
        try {
            $duCounts = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                ->select(
                    'du',
                    \DB::raw('SUM(CASE WHEN contract_type = "TM" THEN 1 ELSE 0 END) as TM'),
                    \DB::raw('SUM(CASE WHEN contract_type = "FF" THEN 1 ELSE 0 END) as FF')
                )
                ->where('contract_status', '=', 'Active')
                ->groupBy('du')
                ->orderBy('du')
                ->get();
            return response()->json([$duCounts]);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    public function getAllContractsRevenue()
    {
        $contracts = Contracts::all();
        $contractDetails = [];

        foreach ($contracts as $contract) {
            $startDate = Carbon::parse($contract->start_date);
            $endDate = Carbon::parse($contract->end_date);
            $duration = $endDate->diffInMonths($startDate);

            $contractDetails[] = [
                'contract_id' => $contract->id,
                'duration_months' => $duration,
                'estimated_amount' => $contract->estimated_amount,
            ];
        }

        return response()->json($contractDetails);
    }

    public function topRevenueRegions()
    {
        $regions = Contracts::selectRaw('msas.region, SUM(contracts.estimated_amount) as total_amount')
            ->join('msas', 'contracts.msa_id', '=', 'msas.id')
            ->groupBy('msas.region')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return response()->json($regions);
    }
    public function getContractCount(Request $request)
    {
        try {
            $querydata = DB::table('contracts')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(contract_status = "Active") as active'),
                    DB::raw('SUM(contract_status = "On Progress") as progress'),
                    DB::raw('SUM(contract_status = "Expiring") as expiring'),
                    DB::raw('SUM(contract_status = "Closed") as closed'),
                    DB::raw('SUM(contract_status = "Expired") as Expired')
                )
                ->first();
            return response()->json(["data" => $querydata]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function getTopContractRegions()
    {
        try {
            $querydata = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->select('region', DB::raw('COUNT(*) AS contractCount'))
                ->groupBy('region')
                ->orderByDesc('contractCount')
                ->limit(5)->get();
            return response()->json(["data" => $querydata]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}