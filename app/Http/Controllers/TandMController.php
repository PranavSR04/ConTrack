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
            'contract_id' => 101,
            'tm_milestone_desc' => 'Initial Consultation',
            'tm_milestone_enddate' => '2024-03-15',
            'tm_amount' => 5000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Project Kickoff',
            'tm_milestone_enddate' => '2024-04-10',
            'tm_amount' => 10000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Design Completion',
            'tm_milestone_enddate' => '2024-05-20',
            'tm_amount' => 15000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Back-end Development',
            'tm_milestone_enddate' => '2024-06-30',
            'tm_amount' => 20000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Front-end Development',
            'tm_milestone_enddate' => '2024-08-15',
            'tm_amount' => 25000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Testing and QA',
            'tm_milestone_enddate' => '2024-09-30',
            'tm_amount' => 30000,
        ],
        [
            'contract_id' => 101,
            'tm_milestone_desc' => 'Final Delivery',
            'tm_milestone_enddate' => '2024-11-10',
            'tm_amount' => 35000,
        ],
    [
            'contract_id' => 103,
            'tm_milestone_desc' => 'Requirement Gathering',
            'tm_milestone_enddate' => '2024-03-15',
            'tm_amount' => 5000,
        ],
        [
            'contract_id' => 103,
            'tm_milestone_desc' => 'Prototype Development',
            'tm_milestone_enddate' => '2024-04-10',
            'tm_amount' => 10000,
        ],
        [
            'contract_id' => 103,
            'tm_milestone_desc' => 'User Acceptance Testing',
            'tm_milestone_enddate' => '2024-05-20',
            'tm_amount' => 15000,
        ],
        [
            'contract_id' => 103,
            'tm_milestone_desc' => 'Beta Release',
            'tm_milestone_enddate' => '2024-06-30',
            'tm_amount' => 20000,
        ],
        [
            'contract_id' => 103,
            'tm_milestone_desc' => 'Product Launch',
            'tm_milestone_enddate' => '2024-08-15',
            'tm_amount' => 25000,
        ],
        [
            'contract_id' => 102,
            'tm_milestone_desc' => 'Market Research',
            'tm_milestone_enddate' => '2024-03-20',
            'tm_amount' => 6000,
        ],
        [
            'contract_id' => 102,
            'tm_milestone_desc' => 'Product Design',
            'tm_milestone_enddate' => '2024-04-15',
            'tm_amount' => 12000,
        ],
        [
            'contract_id' => 102,
            'tm_milestone_desc' => 'Manufacturing Setup',
            'tm_milestone_enddate' => '2024-06-01',
            'tm_amount' => 18000,
        ],
    [
            'contract_id' => 106,
            'tm_milestone_desc' => 'Product Ideation',
            'tm_milestone_enddate' => '2024-03-25',
            'tm_amount' => 5000,
        ],
        [
            'contract_id' => 106,
            'tm_milestone_desc' => 'Market Research and Analysis',
            'tm_milestone_enddate' => '2024-04-10',
            'tm_amount' => 8000,
        ],
        [
            'contract_id' => 106,
            'tm_milestone_desc' => 'Prototyping and Testing',
            'tm_milestone_enddate' => '2024-05-15',
            'tm_amount' => 12000,
        ],
        [
            'contract_id' => 106,
            'tm_milestone_desc' => 'Manufacturing Setup',
            'tm_milestone_enddate' => '2024-06-30',
            'tm_amount' => 20000,
        ],
        [
            'contract_id' => 106,
            'tm_milestone_desc' => 'Product Launch',
            'tm_milestone_enddate' => '2024-08-15',
            'tm_amount' => 25000,
        ],
     
    ];
    foreach ($dummyData as $tmData) {
        $tmData = new TimeAndMaterialContracts($tmData);
        $tmData->save();
}
}
}
