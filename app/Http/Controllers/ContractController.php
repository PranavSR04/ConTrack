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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Util\Percentage;
use app\Models\ActivityLogs;
use Spatie\FlareClient\Http\Exceptions\NotFound;

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

        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
        }
        return response()->json(['Data inserted']);
    }
    public function getContractData(Request $request, $id = null)
    {
        if ($id != null) {
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
                        ->select('contracts.*', 'msas.client_name', 'users.user_name')->get();
                    if ($contractType == 'T&M') {
                        $milestones = TimeAndMaterialContracts::where('tm_contracts.contract_id', '=', $id)
                            ->select('*');
                    } elseif ($contractType == 'Fixed Fee') {
                        $milestones = FixedFeeContracts::where('ff_contracts.contract_id', '=', $id)
                            ->select('*');
                    }
                    $data = $milestones->get();

                    //joining with contract data
                    $combinedData = $singleContract->map(function ($contract) use ($data) {
                        $contract['milestones'] = $data->where('contract_id', $contract['id'])->values()->all();
                        return $contract;
                    });
                    return response()->json($combinedData);
                }
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
        }
        try {
            //get data in request parameter
            $requestData = $request->all();
            $querydata = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
                ->join('users', 'contracts.contract_added_by', '=', 'users.id')
                ->select(
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
                    'contracts.status'
                );
            if (empty($requestData)) {
                return $querydata->paginate('10');
            } else {
                foreach ($requestData as $key => $value) {

                    if (in_array($key, ['contract_ref_id', 'client_name', 'du', 'contract_type', 'msa_ref_id', 'status'])) {
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

                return $querydata->paginate('10');
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

            if ($request->is_active === false) {
                $result = Contracts::where('id', $contractId)->update('is_active', false);
                return response()->json(['message' => 'Contract Closed']);
            }

            $contract_type = Contracts::where('id', $contractId)->value('contract_type');
            if ($contract_type === 'FF') {
                // Validate the incoming request data
                $validator_ff = Validator::make($request->all(), [
                    'msa_id' => 'required|numeric',
                    'contract_added_by' => 'required|numeric',
                    'client_name' => 'string|min:5|max:100',
                    'region' => 'string|max:100',
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date',
                    'date_of_signature' => 'date|before:start_date',
                    'contract_status' => 'required|string',
                    'comments' => 'string',
                    'file' => 'file',
                    'contract_doclink' => 'string',
                    'estimated_amount' => 'required|numeric',
                    'milestones' => 'required|array',
                    'milestones.*.milestone_desc' => 'required|string',
                    'milestones.*.milestone_enddate' => 'required|date',
                    'milestones.*.percentage' => 'required|numeric',
                    'milestones.*.amount' => 'required|numeric',
                    'addendum_file' => 'file',
                    'addendum_doclink' => 'string',
                ]);
                if ($validator_ff->fails()) {
                    return response()->json(['error' => $validator_ff->errors()], 422);
                }


                $validated_ff = $validator_ff->validated();

                $googleDrive = new GoogleDriveController();

                if ($validated_ff['contract_status'] !== "Closed" || $validated_ff['contract_status'] !== "closed" || $validated_ff['contract_status'] !== "CLOSED") {
                    if ($googleDrive->store($request)) {
                        $fileLink = $googleDrive->store($request);
                        $contractUpdateData = [
                            'msa_id' => $validated_ff['msa_id'],
                            'contract_added_by' => $validated_ff['contract_added_by'],
                            'start_date' => $validated_ff['start_date'],
                            'end_date' => $validated_ff['end_date'],
                            'date_of_signature' => $validated_ff['date_of_signature'],
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
                            'contract_status' => $validated_ff['contract_status'],
                            'comments' => $validated_ff['comments'],
                            'contract_doclink' => $validated_ff['contract_doclink'],
                            'estimated_amount' => $validated_ff['estimated_amount'],
                        ];
                    }

                    $associatedUsers = [];
                    foreach ($validated_ff['associated_users'] as $assignee) {
                        if (User::where('id', $assignee['user_id'])->exists()) {
                            $associatedUsers[] = [
                                'user_id' => $assignee['user_id'],
                            ];
                        } else {
                            return response()->json(["User doesn't exist"], 404);
                        }

                    }

                    $milestonesUpdateData = [];

                    foreach ($validated_ff['milestones'] as $milestone) {
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
                            $contractResult = Contracts::where('id', $contractId)->update($contractUpdateData);
                            if ($associatedUsers) {
                                $associatedResult = AssociatedUsers::where('contract_id', $contractId)->update(['user_id' => $associatedUsers]);
                            }

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
                            // $addendumsResult = Addendums::where('contract_id', $contractId)->updateOrCreate(['addendum_doclink' => $validated_ff['addendums_doclink']]);
                            if ($validated_ff['addendum_file']) {
                                $addendum = new AddendumController();
                                $addendum->store($request);
                            }

                            $activityResult = ActivityLogs::create([
                                'contract_id' => $contractId,
                                'performed_by' => $validated_ff['contract_added_by'],
                                'action' => "Updated",
                            ]);

                            return response()->json([
                                "message" => "Contract edited successfully",
                                "data" => [
                                    'contract_result' => $contractResult,
                                    'associated_users_result' => $associatedResult,
                                    'milestones_result' => $ffResult,
                                    'activity_result' => $activityResult
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
                // Validate the incoming request data
                $validator_tm = Validator::make($request->all(), [
                    'msa_id' => 'required|numeric',
                    'contract_added_by' => 'required|numeric',
                    'client_name' => 'string|min:5|max:100',
                    'region' => 'string|max:100',
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date',
                    'date_of_signature' => 'date|before:start_date',
                    'contract_status' => 'required|string',
                    'comments' => 'string',
                    'contract_doclink' => 'string',
                    'file' => 'file',
                    'estimated_amount' => 'required|numeric',
                    'milestones' => 'required|array',
                    'milestones.*.milestone_desc' => 'required|string',
                    'milestones.*.milestone_enddate' => 'required|date',
                    'milestones.*.amount' => 'required|numeric',
                    'associated_users' => 'array',
                    'associated_users.*.user_id' => 'numeric',
                    'addendum_file' => 'file',
                    'addendum_doclink' => 'string',
                ]);
                if ($validator_tm->fails()) {
                    return response()->json(['error' => $validator_tm->errors()], 422);
                }

                $validated_tm = $validator_tm->validated();

                $googleDrive = new GoogleDriveController();

                if ($validated_tm['contract_status'] !== "Closed" || $validated_tm['contract_status'] !== "closed" || $validated_tm['contract_status'] !== "CLOSED") {
                    if ($googleDrive->store($request)) {
                        $fileLink = $googleDrive->store($request);
                        $contractUpdateData = [
                            'msa_id' => $validated_tm['msa_id'],
                            'contract_added_by' => $validated_tm['contract_added_by'],
                            'start_date' => $validated_tm['start_date'],
                            'end_date' => $validated_tm['end_date'],
                            'date_of_signature' => $validated_tm['date_of_signature'],
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
                            'contract_status' => $validated_tm['contract_status'],
                            'comments' => $validated_tm['comments'],
                            'estimated_amount' => $validated_tm['estimated_amount'],
                            'contract_doclink' => $validated_tm['contract_doclink'],
                        ];
                    }

                    // $result = Contracts::where('id', $contractId)->update($contractUpdateData);

                    $milestonesUpdateData = [];

                    foreach ($validated_tm['milestones'] as $milestone) {
                        $milestonesUpdateData[] = [
                            'milestone_desc' => $milestone['milestone_desc'],
                            'milestone_enddate' => $milestone['milestone_enddate'],
                            'amount' => $milestone['amount'],
                        ];
                    }

                    $sumAmounts = array_sum(array_column($milestonesUpdateData, 'amount'));

                    $associatedUsers = [];
                    foreach ($validated_tm['associated_users'] as $assignee) {
                        if (User::where('id', $assignee['user_id'])->exists()) {
                            $associatedUsers[] = [
                                'user_id' => $assignee['user_id'],
                            ];
                        } else {
                            return response()->json(["User doesn't exist"], 404);
                        }

                    }

                    if ($sumAmounts == $validated_tm['estimated_amount']) {
                        $contractResult = Contracts::where('id', $contractId)->update($contractUpdateData);
                        if ($associatedUsers) {
                            $associatedResult = AssociatedUsers::where('contract_id', $contractId)->update(['user_id' => $associatedUsers]);
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

                        // $addendumsResult = Addendums::where('contract_id', $contractId)->updateOrCreate(['addendum_doclink' => $validated_tm['addendums_doclink']]);
                        if ($validated_tm['addendum_file']) {
                            $addendum = new AddendumController();
                            $addendum->store($request);
                        }

                        $activityResult = ActivityLogs::create([
                            'contract_id' => $contractId,
                            'performed_by' => $validated_tm['contract_added_by'],
                            'action' => "Updated",
                        ]);

                        return response()->json([
                            "message" => "Contract edited successfully",
                            "data" => [
                                'contract_result' => $contractResult,
                                'associated_users_result' => $associatedResult,
                                'milestones_result' => $tmResult,
                                'activity_result' => $activityResult
                            ]
                        ]);
                    } else {
                        return response()->json(['error' => "Sum of amount not equal to estimated amount"], 422);
                    }
                } else{
                    return response()->json(['error' => "Contract was closed"], 422);
                }


            } else {
                return response()->json(["error" => "Invalid Contract Type"], 422);
            }
        } catch (Exception $e) {
            return response()->json(['error' => "Failed to edit contract"], 500);
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
            'contract_doclink' => 'string',
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

            $googleDrive = new GoogleDriveController();

            if ($googleDrive->store($request)) {
                $fileLink = $googleDrive->store($request);
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
                    'is_active' => true,
                    'estimated_amount' => $request->estimated_amount,
                    'comments' => $request->comments,
                    'contract_doclink' => "",
                ]);
            }

            $contractId = $contract->id;
            // return response ()->json([$request->assoc[0]]);
            if (!empty($request->assoc)) {
                foreach ($request->assoc as $users) {
                    // return response()->json([$users['user_id']]);
                    // if (!User::find($users->user_id)) {
                    //     throw new Exception("User not Found");
                    //     // return response()->json(["User doesn't exist"], 404);
                    // }
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
            if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                return response()->json(['error' => 'User not valid'], 500);

            } else {
                return response()->json(['error' => 'Failed to create contract', 'message' => $e->getMessage()], 500);

            }

        }
    }


}