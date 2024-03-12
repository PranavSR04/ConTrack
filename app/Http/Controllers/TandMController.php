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
            'milestone_desc' => 'Project Kickoff',
            'milestone_enddate' => now()->addDays(15),
            'amount' => 550000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'Phase 1 Completion',
            'milestone_enddate' => now()->addMonths(2),
            'amount' => 550000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'Midway Progress',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 550000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'Project Completion',
            'milestone_enddate' => now()->addMonths(8),
            'amount' => 550000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Project Initiation',
            'milestone_enddate' => now()->addDays(10),
            'amount' => 700000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Design and Planning',
            'milestone_enddate' => now()->addMonths(2),
            'amount' => 900000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'Development Milestone',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 800000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Project Initiation',
            'milestone_enddate' => now()->addDays(10),
            'amount' => 100000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Requirements Analysis',
            'milestone_enddate' => now()->addMonths(2),
            'amount' => 150000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Design and Planning',
            'milestone_enddate' => now()->addMonths(3),
            'amount' => 75000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Development Milestone 1',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 125000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Testing and QA',
            'milestone_enddate' => now()->addMonths(7),
            'amount' => 100000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'Project Completion',
            'milestone_enddate' => now()->addMonths(8),
            'amount' => 50000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Project Initiation',
            'milestone_enddate' => now()->addDays(10),
            'amount' => 1000000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Requirements Analysis',
            'milestone_enddate' => now()->addMonths(2),
            'amount' => 1500000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Design and Planning',
            'milestone_enddate' => now()->addMonths(3),
            'amount' => 750000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Development Milestone 1',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 1250000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Testing and QA',
            'milestone_enddate' => now()->addMonths(7),
            'amount' => 3500000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'Project Completion',
            'milestone_enddate' => now()->addMonths(8),
            'amount' => 3200000.00,
        ],
        [
            'contract_id' => 11,
            'milestone_desc' => 'Project Initiation',
            'milestone_enddate' => now()->addDays(10),
            'amount' => 200000.00,
        ],
        [
            'contract_id' => 11,
            'milestone_desc' => 'Design and Planning',
            'milestone_enddate' => now()->addMonths(2),
            'amount' => 500000.00,
        ],
        [
            'contract_id' => 11,
            'milestone_desc' => 'Development Milestone',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 500000.00,
        ],

    ];

    foreach ($dummyData as $tmData) {
        $tmData = new TimeAndMaterialContracts($tmData);
        $tmData->save();
}
return response()->json(['Data inserted']);
}
}
