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
            'contract_id' => 5,
            'milestone_desc' => 'On BRD SignOff',
            'milestone_enddate' => now()->addMonths(14),
            'amount' => 4000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 5,
            'milestone_desc' => 'On Completion of Figma Design',
            'milestone_enddate' => now()->addMonths(16),
            'amount' => 6000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 5,
            'milestone_desc' => 'On Completion of Api Creation',
            'milestone_enddate' => now()->addMonths(26),
            'amount' => 4000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 5,
            'milestone_desc' => 'On Completion of UI',
            'milestone_enddate' => now()->addMonths(36),
            'amount' => 5000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 5,
            'milestone_desc' => 'On Deployment',
            'milestone_enddate' => now()->addMonths(44),
            'amount' => 5000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'On BRD SignOff',
            'milestone_enddate' => now()->addMonths(9),
            'amount' => 8000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'On Completion of Figma Design',
            'milestone_enddate' => now()->addMonths(12),
            'amount' => 4000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'On Completion of Api Creation',
            'milestone_enddate' => now()->addMonths(18),
            'amount' => 10000.00,
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'On Completion of UI',
            'milestone_enddate' => now()->addMonths(24),
            'amount' => 8000.00,
        ],
        [
            'contract_id' => 6,
            'milestone_desc' => 'On Deployment',
            'milestone_enddate' => now()->addMonths(28),
            'amount' => 10000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'On BRD SignOff',
            'milestone_enddate' => now()->addMonths(7),
            'amount' => 8000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'On Completion of Figma Design',
            'milestone_enddate' => now()->addMonths(15),
            'amount' => 4000.00, // Adjusted amount to ensure the sum equals $2,200,000
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'On Completion of Api Creation',
            'milestone_enddate' => now()->addMonths(26),
            'amount' => 12000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'On Completion of UI',
            'milestone_enddate' => now()->addMonths(44),
            'amount' => 8000.00,
        ],
        [
            'contract_id' => 7,
            'milestone_desc' => 'On Deployment',
            'milestone_enddate' => now()->addMonths(60),
            'amount' => 13000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'On BRD SignOff',
            'milestone_enddate' => now()->addMonths(5),
            'amount' => 4000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'On Completion of Api Creation',
            'milestone_enddate' => now()->addMonths(13),
            'amount' => 5000.00,
        ],
        [
            'contract_id' => 8,
            'milestone_desc' => 'On Deployment',
            'milestone_enddate' => now()->addMonths(30),
            'amount' => 5000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'On BRD SignOff',
            'milestone_enddate' => now()->addMonths(8),
            'amount' => 4000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'On Completion of Api Creation',
            'milestone_enddate' => now()->addMonths(23),
            'amount' => 5000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'On Completion of UI',
            'milestone_enddate' => now()->addMonths(33),
            'amount' => 8000.00,
        ],
        [
            'contract_id' => 9,
            'milestone_desc' => 'On Deployment',
            'milestone_enddate' => now()->addMonths(38),
            'amount' => 5000.00,
        ],

    ];



     
    
    foreach ($dummyData as $tmData) {
        $tmData = new TimeAndMaterialContracts($tmData);
        $tmData->save();
}
return response()->json(['Data inserted']);
}
}
