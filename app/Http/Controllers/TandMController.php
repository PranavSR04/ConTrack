<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use App\Models\TimeAndMaterialContracts;
use Illuminate\Http\Request;

class TandMController extends Controller
{
    public function insertTandMData()
    {
    $dummyData = [
        [
            'contract_id' => 6,
            'milestone_desc' => 'Initial Consultation',
            'milestone_enddate' => '2024-03-15',
            'amount' => 5000,
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'Project Kickoff',
            'milestone_enddate' => '2024-04-10',
            'amount' => 10000,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Design Completion',
            'milestone_enddate' => '2024-05-20',
            'amount' => 15000,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Back-end Development',
            'milestone_enddate' => '2024-06-30',
            'amount' => 20000,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Front-end Development',
            'milestone_enddate' => '2024-08-15',
            'amount' => 25000,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Testing and QA',
            'milestone_enddate' => '2024-09-30',
            'amount' => 30000,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Final Delivery',
            'milestone_enddate' => '2024-11-10',
            'amount' => 35000,
        ],
    [
            'contract_id' => 8,
            'milestone_desc' => 'Requirement Gathering',
            'milestone_enddate' => '2024-03-15',
            'amount' => 5000,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Prototype Development',
            'milestone_enddate' => '2024-04-10',
            'amount' => 10000,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'User Acceptance Testing',
            'milestone_enddate' => '2024-05-20',
            'amount' => 15000,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Beta Release',
            'milestone_enddate' => '2024-06-30',
            'amount' => 20000,
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'Product Launch',
            'milestone_enddate' => '2024-08-15',
            'amount' => 25000,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Market Research',
            'milestone_enddate' => '2024-03-20',
            'amount' => 6000,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Product Design',
            'milestone_enddate' => '2024-04-15',
            'amount' => 12000,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Manufacturing Setup',
            'milestone_enddate' => '2024-06-01',
            'amount' => 18000,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Prototyping and Testing',
            'milestone_enddate' => '2024-05-15',
            'amount' => 12000,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Manufacturing Setup',
            'milestone_enddate' => '2024-06-30',
            'amount' => 20000,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Product Launch',
            'milestone_enddate' => '2024-08-15',
            'amount' => 25000,
        ],
     
    ];
    foreach ($dummyData as $tmData) {
        $tmData = new TimeAndMaterialContracts($tmData);
        $tmData->save();
}
return response()->json(['Data inserted']);
}
}
