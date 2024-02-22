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
                'contract_ref_id' => 'AGF7',
                'msa_ref_id' => 1,
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
                'msa_ref_id' => 1,
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
                'msa_ref_id' => 1,
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
                'msa_ref_id' => 3,
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
                'msa_ref_id' => 5,
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
                'msa_ref_id' => 2,
                'contract_added_by' => 1,
                'contract_type' => "TandM",
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
                'msa_ref_id' => 5,
                'contract_added_by' => 1,
                'contract_type' => "TandM",
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
                'msa_ref_id' => 1,
                'contract_added_by' => 1,
                'contract_type' => "TandM",
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
                'msa_ref_id' => 4,
                'contract_added_by' => 4,
                'contract_type' => "TandM",
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
        $querydata = Contracts::join('msas', 'contracts.msa_ref_id', '=', 'msas.id')
            ->join('users', 'contracts.contract_added_by', '=', 'users.id')
            ->select('contracts.*', 'msas.client_name', 'users.username');
        if (empty($individualContract)) {
            return $querydata->get();
        } else {
            foreach ($individualContract as $key => $value) {
                if (in_array($key, ['id', 'name', 'date', 'contract_type', 'status'])) {
                    $querydata->where('contracts.' . $key, $value);
                }
                return $querydata->get();
            }

        }
    }
}
