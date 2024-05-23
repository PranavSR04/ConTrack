<?php
namespace App\Services;

use App\Http\Controllers\ActivityLogInsertController;
use App\Models\ActivityLogs;
use App\ServiceInterfaces\ContractInterface;
use App\Models\Addendums;
use App\Models\AssociatedGroups;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
                        ->select('contracts.*','msas.msa_ref_id', 'msas.client_name', 'users.user_name', 'msas.region')->get();
                    //get milestone based on contract type  
                    if ($contractType == 'TM') {
                        $milestones = TimeAndMaterialContracts::where('tm_contracts.contract_id', '=', $id)
                            ->select('id', 'contract_id', 'milestone_desc', 'milestone_enddate', 'amount');
                    } elseif ($contractType == 'FF') {
                        $milestones = FixedFeeContracts::where('ff_contracts.contract_id', '=', $id)
                            ->select(
                                'id',
                                'contract_id',
                                'milestone_desc',
                                'milestone_enddate',
                                'percentage',
                                'amount'
                            );
                    }
                    $data = $milestones->get();
                    //joining with contract data
                    $combinedData = $singleContract->map(function ($contract) use ($data) {
                        $contract['milestones'] = $data->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });

                    //get all addendums
                    $addendum = Addendums::where('contract_id', '=', $id)
                        ->select('id', 'contract_id', 'addendum_doclink')
                        ->get();
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($addendum) {
                        $contract['addendum'] = $addendum->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });

                    //get all associated users
                    $associatedUsers = AssociatedUsers::join('users', 'associated_users.user_id', '=', 'users.id')
                        ->where('contract_id', '=', $id)
                        ->select('associated_users.id', 'contract_id', 'user_name', 'users.id as user_id', 'user_mail')
                        ->get();
                    //join the data
                    $combinedData = $combinedData->map(function ($contract) use ($associatedUsers) {
                        $contract['associated_users'] = $associatedUsers->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });
                     //get all associated groups
                     $associatedGroups = AssociatedGroups::
                     join('group', 'associated_groups.group_id', '=', 'group.id')
                     ->where('contract_id', '=', $id)
                     ->select('associated_groups.id', 'contract_id','group.id as group_id', 'group_name')
                     ->get();
                 //join the data
                 $combinedData = $combinedData->map(function ($contract) use ($associatedGroups) {
                     $contract['associated_groups'] = $associatedGroups->where('contract_id', $contract['id'])->values()->all();
                     return $contract;
                 });
                    return response()->json(["data" => $combinedData], 200);
                }
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 404);
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
                    'contracts.start_date',
                    'contracts.end_date',
                    'contracts.du',
                    'contracts.contract_status'
                );
            if (empty($requestData)) {
                return $querydata->paginate($paginate);
            } else {
                //add search conditions
                foreach ($requestData as $key => $value) {
                    if (in_array($key, ['contract_ref_id', 'client_name', 'du', 'contract_type', 'msa_ref_id', 'contract_status'])) {
                        $querydata->where($key, 'LIKE', '%' . $value . '%');
                    }
                    if (in_array($key, ['start_date', 'end_date'])) {
                        $querydata->where('contracts.' . $key, 'LIKE', '%' . $value . '%');
                    }
                }
                if ($request->status) {
                    $querydata->where('contract_status', '=', $request->status);
                } else {
                    //exclude expired default
                    $querydata->where('contract_status', '!=', 'Expired');
                }
                if ($request->sort_by) {
                    $querydata->orderBy($request->sort_by, $request->sort_value);
                } else {
                    $querydata->orderBy('contracts.updated_at', 'desc'); //default sort
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

            if (!$contract) {
                return response()->json(['error' => 'Contract not found'], 404);
            }

            // Checking whether contract needed to be closed
            if ($request->contract_status === "Closed") {
                $result = Contracts::where('id', $contractId)->update(['contract_status' => "Closed"]);
                $action = "Closed";
                $activityLogInsertService = new ActivityLogInsertService();
                $insertController = new ActivityLogInsertController($activityLogInsertService);
                $insertController->addToActivityLog($contractId, $contract->msa_id, $request->contract_added_by, "Closed");
                return response()->json(['message' => 'Contract Closed']);
            }

            $contract_type = Contracts::where('id', $contractId)->value('contract_type');

            if ($contract_type === 'FF') {
                // Parsing string data into array
                $decodedMilestones = $request->milestones;
                if (!is_array(($request->milestones))) {
                    $decodedMilestones = json_decode($request->milestones, true);
                }

                $decodedAssociatedUsers = $request->associated_users;
                if (!is_array(($request->associated_users))) {
                    $decodedAssociatedUsers = json_decode($request->associated_users, true);
                }

                $decodedAssociatedGroups = $request->associated_groups;
                if (!is_array(($request->associated_groups))) {
                    $decodedAssociatedGroups = json_decode($request->associated_groups, true);
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
                    'contract_status' => 'string',
                    'du' => 'required|string',
                    'comments' => 'string',
                    'contract_doclink' => 'string',
                    'estimated_amount' => 'required|numeric',
                    "milestones" => ['required', 'array'],
                    'milestones.*.id' => 'numeric',
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

                if ($request->contract_status !== "Closed" || $request->contract_status !== "closed" || $request->contract_status !== "CLOSED") {
                    // Checking only to update the data of contracts which are not having status as closed
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

                    $sumPercentages = array_sum(array_column($decodedMilestones, 'percentage'));
                    $sumAmounts = array_sum(array_column($decodedMilestones, 'amount'));

                    if ($sumPercentages == 100) {
                        if ($sumAmounts == $validated_ff['estimated_amount']) {
                            // Insertion starts only after all validation finishes
                            Contracts::where('id', $contractId)->update($contractUpdateData);
                            $contractResult = Contracts::where('id', $contractId)->get();

                            // Store associated user IDs related to the contract in an array
                            $db_associated_users = AssociatedUsers::where('contract_id', $contractId)->pluck('user_id')->toArray();

                            // For enterting data into Associated Users table
                            $associated_users = null;
                            if (!empty($request->associated_users)) {
                                foreach ($decodedAssociatedUsers as $user_id) {
                                    $userId = $user_id;
                                    // Remove this user ID from the $db_associated_users array as it's still in use
                                    unset($db_associated_users[array_search($user_id, $db_associated_users)]);

                                    AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                                    $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                                }
                            }

                            // Delete associations for users that are not in the request
                            if (!empty($db_associated_users)) {
                                AssociatedUsers::where('contract_id', $contractId)->whereIn('user_id', $db_associated_users)->delete();
                            }

                            // Store associated group IDs related to the contract in an array
                            $db_associated_groups = AssociatedGroups::where('contract_id', $contractId)->pluck('group_id')->toArray();

                            // For enterting data into Associated group table
                            $associated_groups = null;
                            if (!empty($request->associated_groups)) {
                                foreach ($decodedAssociatedGroups as $group_id) {
                                    $groupId = $group_id;
                                    // Remove this user ID from the $db_associated_groups array as it's still in use
                                    unset($db_associated_groups[array_search($group_id, $db_associated_groups)]);

                                    AssociatedGroups::where('contract_id', $contractId)->updateOrCreate(['group_id' => $groupId, 'contract_id' => $contractId]);
                                    $associated_groups = AssociatedGroups::where('contract_id', $contractId)->get();
                                }
                            }

                            // Delete associations for groups that are not in the request
                            if (!empty($db_associated_groups)) {
                                AssociatedGroups::where('contract_id', $contractId)->whereIn('group_id', $db_associated_groups)->delete();
                            }

                            // Store milestone ids related to the contract in an array
                            $db_milestones = FixedFeeContracts::where('contract_id', $contractId)->pluck('id')->toArray();

                            foreach ($decodedMilestones as $milestone) {
                                // For enterting data into Fixed fee table
                                if (isset($milestone['id']) && $milestone['id'] !== '') {
                                    $existingMilestone = FixedFeeContracts::where('id', $milestone['id'])->first();
                                    if ($existingMilestone) {
                                        // If the milestone exists, update its data
                                        $ffResult = FixedFeeContracts::where('id', $existingMilestone->id)->update([
                                            'contract_id' => $contractId,
                                            'milestone_desc' => $milestone['milestone_desc'],
                                            'milestone_enddate' => $milestone['milestone_enddate'],
                                            'percentage' => $milestone['percentage'],
                                            'amount' => $milestone['amount'],
                                        ]);
                                        $ffResult = FixedFeeContracts::find($existingMilestone->id);
                                        // Remove this milestone id from the $db_milestones array as it's still in use
                                        unset($db_milestones[array_search($milestone['id'], $db_milestones)]);
                                    }
                                } else {
                                    // If new milestone, create it.
                                    $ffResult = FixedFeeContracts::create(
                                        [
                                            'contract_id' => $contractId,
                                            'milestone_desc' => $milestone['milestone_desc'],
                                            'milestone_enddate' => $milestone['milestone_enddate'],
                                            'percentage' => $milestone['percentage'],
                                            'amount' => $milestone['amount'],
                                        ]
                                    );
                                }
                            }

                            // Delete milestones that are not in the request
                            if (!empty($db_milestones)) {
                                FixedFeeContracts::whereIn('id', $db_milestones)->delete();
                            }

                            // Insertion into addendum table and addendum uploaded to drive
                            if (isset($validated_ff['addendum_file']) && $validated_ff['addendum_file'] !== '') {
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
                                    'associatedusers_result' => $associated_users,
                                    'associatedgroups_result' => $associated_groups,
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
                    'contract_status' => 'string',
                    'du' => 'required|string',
                    'comments' => 'string',
                    'contract_doclink' => 'string',
                    'estimated_amount' => 'required|numeric',
                    "milestones" => ['required', 'array'],
                    'milestones.*.id' => 'numeric',
                    'milestones.*.milestone_desc' => 'required|string',
                    'milestones.*.milestone_enddate' => 'required|date',
                    'milestones.*.amount' => 'required|numeric',
                    // 'associated_users' => 'array','exists:users,id',
                    // 'associated_users.*.user_id' => 'numeric',
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

                if ($validated_tm['contract_status'] !== "Closed" || $validated_tm['contract_status'] !== "closed" || $validated_tm['contract_status'] !== "CLOSED") {
                    // Checking only to update the data of contracts which are not having status as closed
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

                    $sumAmounts = array_sum(array_column($decodedMilestones, 'amount'));

                    if ($sumAmounts == $validated_tm['estimated_amount']) {
                        Contracts::where('id', $contractId)->update($contractUpdateData);
                        $contractResult = Contracts::where('id', $contractId)->get();

                        // Store associated user IDs related to the contract in an array
                        $db_associated_users = AssociatedUsers::where('contract_id', $contractId)->pluck('user_id')->toArray();

                        // For enterting data into Associated Users table
                        $associated_users = null;
                        if (!empty($request->associated_users)) {
                            foreach ($decodedAssociatedUsers as $user_id) {
                                $userId = $user_id;
                                // Remove this user ID from the $db_associated_users array as it's still in use
                                unset($db_associated_users[array_search($user_id, $db_associated_users)]);

                                AssociatedUsers::where('contract_id', $contractId)->updateOrCreate(['user_id' => $userId, 'contract_id' => $contractId]);
                                $associated_users = AssociatedUsers::where('contract_id', $contractId)->get();
                            }
                        }

                        // Delete associations for users that are not in the request
                        if (!empty($db_associated_users)) {
                            AssociatedUsers::where('contract_id', $contractId)->whereIn('user_id', $db_associated_users)->delete();
                        }
                         // Store associated group IDs related to the contract in an array
                         $db_associated_groups = AssociatedGroups::where('contract_id', $contractId)->pluck('group_id')->toArray();

                         // For enterting data into Associated group table
                         $associated_groups = null;
                         if (!empty($request->associated_groups)) {
                             foreach ($db_associated_groups as $group_id) {
                                 $groupId = $group_id;
                                 // Remove this user ID from the $db_associated_groups array as it's still in use
                                 unset($db_associated_groups[array_search($group_id, $db_associated_groups)]);

                                 AssociatedGroups::where('contract_id', $contractId)->updateOrCreate(['group_id' => $groupId, 'contract_id' => $contractId]);
                                 $associated_groups = AssociatedGroups::where('contract_id', $contractId)->get();
                             }
                         }

                         // Delete associations for groups that are not in the request
                         if (!empty($db_associated_groups)) {
                             AssociatedGroups::where('contract_id', $contractId)->whereIn('group_id', $db_associated_groups)->delete();
                         }

                        // Store milestone ids related to the contract in an array
                        $db_milestones = TimeAndMaterialContracts::where('contract_id', $contractId)->pluck('id')->toArray();

                        foreach ($decodedMilestones as $milestone) {
                            // For enterting data into Fixed fee table
                            if (isset($milestone['id']) && $milestone['id'] !== '') {
                                $existingMilestone = TimeAndMaterialContracts::where('id', $milestone['id'])->first();
                                if ($existingMilestone) {
                                    // If the milestone exists, update its data
                                    $tmResult = TimeAndMaterialContracts::where('id', $existingMilestone->id)->update([
                                        'contract_id' => $contractId,
                                        'milestone_desc' => $milestone['milestone_desc'],
                                        'milestone_enddate' => $milestone['milestone_enddate'],
                                        'amount' => $milestone['amount'],
                                    ]);
                                    $tmResult = TimeAndMaterialContracts::find($existingMilestone->id);
                                    // Remove this milestone id from the $db_milestones array as it's still in use
                                    unset($db_milestones[array_search($milestone['id'], $db_milestones)]);
                                }
                            } else {
                                // If new milestone, create it.
                                $tmResult = TimeAndMaterialContracts::create(
                                    [
                                        'contract_id' => $contractId,
                                        'milestone_desc' => $milestone['milestone_desc'],
                                        'milestone_enddate' => $milestone['milestone_enddate'],
                                        'amount' => $milestone['amount'],
                                    ]
                                );
                            }
                        }

                        // Delete milestones that are not in the request
                        if (!empty($db_milestones)) {
                            TimeAndMaterialContracts::whereIn('id', $db_milestones)->delete();
                        }

                        // Insertion into addendum table and addendum uploaded to drive
                        if (isset($validated_tm['addendum_file']) && $validated_tm['addendum_file'] !== '') {
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
                                'milestones_result' => $tmResult,
                                'associatedusers_result' => $associated_users,
                                'associatedgroups_result' => $associated_groups,

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
        // return response()->json([$request->all()]);
        // Validate the incoming request
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
            'file' => 'file|required',
            'associated_users' => ['array', 'exists:users,id'],
            'associated_groups' => ['array', 'exists:group,id']
            // 'associated_users.*.user_id' => 'required|numeric',
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            $totalAmount = 0;
            $totalPercentage = 0;
            $decodedMilestones = $request->milestone;

            // Decode JSON milestone if needed
            if (!is_array(($request->milestones))) {   //ch 
                $decodedMilestones = json_decode($request->milestones, true);  //ch
            }

            // Handle invalid JSON format for milestones
            if ($decodedMilestones === null && json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON format for milestones'], 422, ["content-type" => "application/json"]);
            }


            // Calculate total amount and percentage based on contract type
            if ($request->contract_type === 'FF') {

                if (!empty($request->milestones)) {  //ch
                    try {
                        if (is_array($request->milestones)) {
                            foreach ($request->milestones as $milestone) {
                                $totalPercentage += $milestone['percentage'];
                                $totalAmount += $milestone['amount'];
                            }
                        } else {
                            $milestones = json_decode($request->milestones, true);
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
                if (!empty($request->milestones)) {
                    if (is_array($request->milestones)) {
                        foreach ($request->milestones as $milestone) {
                            $totalAmount += $milestone['amount'];
                        }
                    } else {
                        foreach (json_decode($request->milestones, true) as $milestone) {
                            $totalAmount += $milestone['amount'];
                        }
                    }
                } else {
                    return response()->json(['error' => 'No milestones provided for Time and Material contract.'], 422);
                }
            }
            // Validate milestone amounts for FF contract
            if ($request->contract_type === 'FF' && ($totalPercentage !== 100 || floatval($totalAmount) !== floatval($request->estimated_amount))) {
                return response()->json(['error' => 'Invalid milestones for Fixed Fee contract.'], 404);
            }
            // Validate milestone amounts for TM contract
            if ($request->contract_type === 'TM' && $totalAmount !== (int) $request->estimated_amount) {
                return response()->json(['error' => 'Invalid milestones for Time and Material contract.'], 404);
            }
            $googleDrive = new GoogleDriveService();
            $fileLink = $googleDrive->store($request);

            // Create the contract
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
            $contractId = $contract->id;

            $action = "Added";
            $activityLogInsertService = new ActivityLogInsertService();
            $insertController = new ActivityLogInsertController($activityLogInsertService);
            $insertController->addToActivityLog($contractId, $request->msa_id, $request->contract_added_by, $action);
            // Associate users with the contract
            if (!empty($request->associated_users)) {
                foreach ($request->associated_users as $user_id) {
                    $assoc_users_result = AssociatedUsers::create([
                        'contract_id' => $contractId,
                        'user_id' => $user_id,
                    ]);
                }
            }
            //insert associated groups
            if (!empty($request->associated_groups)) {
                foreach ($request->associated_groups as $group_id) {
                    $assoc_users_result = AssociatedGroups::create([
                        'contract_id' => $contractId,
                        'group_id' => $group_id,
                    ]);
                }
            }

            // Create Fixed Fee Contracts if contract type is FF
            if ($request->contract_type === 'FF') {
                try {
                    if (is_array($request->milestones) && !empty($request->milestones)) {
                        try {
                            foreach ($request->milestones as $milestone) {
                                $ffresult = FixedFeeContracts::create([
                                    'contract_id' => $contractId,
                                    'milestone_desc' => $milestone['milestone_desc'],
                                    'milestone_enddate' => Carbon::parse($milestone['milestone_enddate'])->format('Y-m-d'),
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
                        ['contract_result' => $contract, 'associated_users_result' => !empty($assoc_users_result) ? $assoc_users_result : "Nil", "milestone_result" => $ffresult]
                ], 200);
            }
            // Create Time and Material Contracts if contract type is TM
            else if ($request->contract_type === 'TM') {
                if (is_array($request->milestones) && !empty($request->milestones)) {
                    foreach ($request->milestones as $milestone) {
                        $tmresult = TimeAndMaterialContracts::create([
                            'contract_id' => $contractId,
                            'milestone_desc' => $milestone['milestone_desc'],
                            'milestone_enddate' => Carbon::parse($milestone['milestone_enddate'])->format('Y-m-d'),
                            'amount' => $milestone['amount'],
                        ]);
                    }
                    return response()->json([
                        'message' => 'Contract created successfully',
                        'data' =>
                            ['contract_result' => $contract, !empty($assoc_users) ? $assoc_users : "Nil", "milestone_result" => $tmresult]
                    ], 200);
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
            // Fetch DU counts and contract types
            $duCounts = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                ->select(
                    'du',
                    \DB::raw('SUM(CASE WHEN contract_type = "TM" THEN 1 ELSE 0 END) as TM'),
                    \DB::raw('SUM(CASE WHEN contract_type = "FF" THEN 1 ELSE 0 END) as FF')
                )
                ->groupBy('du')
                ->orderBy('du')
                ->get();

            // Count total number of contracts
            $totalContractsCount = Contracts::count();
            // Return JSON response with DU counts and total contracts count
            return response()->json([
                'duCounts' => $duCounts,
                'totalContractsCount' => $totalContractsCount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Database error'], 500);

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

        return response()->json($contractDetails, 200);
    }

    public function topRevenueRegions()
    {
        try {
            $regions = Contracts::selectRaw('msas.region, SUM(contracts.estimated_amount) as total_amount')
                ->join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->groupBy('msas.region')
                ->orderByDesc('total_amount')
                ->limit(3)
                ->get();

            return response()->json($regions);
        } catch (QueryException $e) {
            if (strpos($e->getMessage(), 'Unknown column') !== false) {
                return response()->json(['error' => 'Database error: Column not found'], 500);
            } else {
                // If it's not the specific error, return a generic database error message
                return response()->json(['error' => 'Database error'], 500);
            }
        }
    }
    public function getContractCount()
    {
        try {
            $querydata = Contracts::selectRaw('
            COUNT(*) as total,
            SUM(contract_status = "Active") as active,
            SUM(contract_status = "On Progress") as progress,
            SUM(contract_status = "Expiring") as expiring,
            SUM(contract_status = "Closed") as closed,
            SUM(contract_status = "Expired") as Expired ')
                ->first();
            return response()->json(["data" => $querydata], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function getTopContractRegions() //region with highest contracts
    {
        try {
            $querydata = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->select('region', DB::raw('COUNT(*) AS contractCount'))
                ->groupBy('region')
                ->orderByDesc('contractCount')
                ->limit(5)->get();
            return response()->json(["data" => $querydata], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}