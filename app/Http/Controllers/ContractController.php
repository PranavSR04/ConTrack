<?php

namespace App\Http\Controllers;

use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Validator;

class ContractController extends Controller
{
    public function insertContractsData()
    {
        $contractsDataArray = [
            [
                'contract_ref_id' => 'AGF7',
                'msa_id' => 1,
                'contract_added_by' => 2,
                'contract_type' => "FF",
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
                'msa_id' => 1,
                'contract_added_by' => 1,
                'contract_type' => "FF",
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
                'msa_id' => 1,
                'contract_added_by' => 2,
                'contract_type' => "FF",
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
                'contract_type' => "FF",
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
                'contract_type' => "FF",
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
                'contract_type' => "tm",
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
                'contract_type' => "tm",
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
                'msa_id' => 1,
                'contract_added_by' => 1,
                'contract_type' => "tm",
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
                'contract_type' => "tm",
                'date_of_signature' => now()->subMonths(2),
                'comments' => "Contact me if it further requires change",
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
    public function getContractData(Request $request)
    {
        $individualContract = $request->all();
        $querydata = Contracts::join('msas', 'contracts.msa_id', '=', 'msas.id')
            ->join('users', 'contracts.contract_added_by', '=', 'users.id')
            ->select('contracts.*', 'msas.client_name', 'users.user_name');
        if (empty($individualContract)) {
            return $querydata->get();
        } else {
            foreach ($individualContract as $key => $value) {
                if (in_array($key, ['contract_ref_id', 'client_name', 'du', 'contract_type', 'msa_ref_id'])) {
                    $querydata->where($key, $value);
                }
            }
            if ($querydata->count() == 0) {
                return response()->json(['error' => 'No data Found'], 404);
            }
            return $querydata->get();

        }
    }


    // public function addContract(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'msa_id' => 'required|exists:msas,id',
    //         'contract_ref_id' => 'required|string|max:25',
    //         'contract_type' => 'required|string|max:25',
    //         'start_date' => 'required|date|before:end_date|after:date_of_signature',
    //         'end_date' => 'required|date|after:start_date',
    //         'date_of_signature' => 'required|date',
    //         'du' => 'required|string',
    //         'estimated_amount' => 'required|numeric|min:0',
    //         'comments' => 'string',
    //         'contract_doclink' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {

    //         // return response()->json(["Hi"]);
    //         return $validator->errors();
    //     }
    //     $validated = $validator->validated();

    //     try {
    //         $contract = Contracts::create([
    //             'msa_id' => $request->msa_id,
    //             'contract_added_by' => $request->contract_added_by,
    //             'contract_ref_id' => $request->contract_ref_id,
    //             'contract_type' => $request->contract_type,
    //             'start_date' => $request->start_date,
    //             'end_date' => $request->end_date,
    //             'date_of_signature' => $request->date_of_signature,
    //             'du' => $request->du,
    //             'is_active' => true,
    //             'estimated_amount' => $request->estimated_amount,
    //             'comments' => $request->comments,
    //             'contract_doclink' => $request->contract_doclink,
    //         ]);
    //         $contractId = $contract->id;

    //         if ($request->contract_type === 'FF') {
    //             $totalPercentage = 0;
    //             $totalAmount = 0;
    //             if (!empty($request->ff_milestones)) {
    //                 foreach ($request->ff_milestones as $milestone) {
    //                     $totalPercentage += $milestone['percentage'];
    //                     $totalAmount += $milestone['amount'];

    //                     FixedFeeContracts::create([
    //                         'contract_id' => $contractId,
    //                         'milestone_desc' => $milestone['milestone_desc'],
    //                         'milestone_enddate' => $milestone['milestone_enddate'],
    //                         'percentage' => $milestone['percentage'],
    //                         'amount' => $milestone['amount'],
    //                     ]);
    //                 }
    //             } else {
    //                 throw new Exception('Error');
    //             }

    //             if ($totalPercentage != 100 || $totalAmount != $request->estimated_amount) {
    //                 $errorMessage = "Invalid Fixed Fee milestones: ";

    //                 if ($totalPercentage != 100) {
    //                     $errorMessage .= "The total percentage of all milestones must equal 100. Current total percentage: $totalPercentage. ";
    //                 }

    //                 if ($totalAmount != $request->estimated_amount) {
    //                     $errorMessage .= "The total amount of all milestones must equal the estimated amount of the contract.  Estimated amount: $request->estimated_amount,  Current total amount: $totalAmount. ";
    //                 }

    //                 return response()->json(['error' => $errorMessage], 400);
    //             }
    //         } else {
    //             if (!empty($request->tm_milestones)) {
    //                 foreach ($request->tm_milestones as $milestone) {
    //                     TimeAndMaterialContracts::create([
    //                         'contract_id' => $contractId,
    //                         'milestone_desc' => $milestone['milestone_desc'],
    //                         'milestone_enddate' => $milestone['milestone_enddate'],
    //                         'amount' => $milestone['amount'],
    //                     ]);
    //                 }
    //             } else {
    //                 throw new Exception('Error');
    //             }
    //         }

    //         return response()->json(['message' => 'Contract created successfully', 'contract' => $contractId], 201);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Failed to create contract', 'message' => $e->getMessage()], 500);
    //     }
    // }






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