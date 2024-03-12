<?php

namespace App\Http\Controllers;

use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use Illuminate\Http\Request;

class FixedFeeController extends Controller
{
    public function insertFixedFeeData()
    {
        $dummydata_ff = [
            [
                'contract_id' => 1,
                'milestone_desc' => 'Product Design and Planning',
                'milestone_enddate' => '2024-05-20',
                'percentage' => 20,
                'amount' => 40000.00, // 20% of $200,000
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Prototype Development',
                'milestone_enddate' => '2024-06-30',
                'percentage' => 30,
                'amount' => 60000.00, // 30% of $200,000
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Prototype Testing and Feedback',
                'milestone_enddate' => '2024-08-15',
                'percentage' => 40,
                'amount' => 80000.00, // 40% of $200,000
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Manufacturing Setup',
                'milestone_enddate' => '2024-09-30',
                'percentage' => 10,
                'amount' => 20000.00, // 10% of $200,000
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Product Design and Planning',
                'milestone_enddate' => '2024-05-20',
                'percentage' => 20,
                'amount' => 50000.00, // 20% of $250,000
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Prototype Development',
                'milestone_enddate' => '2024-06-30',
                'percentage' => 30,
                'amount' => 75000.00, // 30% of $250,000
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Prototype Testing and Feedback',
                'milestone_enddate' => '2024-08-15',
                'percentage' => 40,
                'amount' => 100000.00, // 40% of $250,000
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Manufacturing Setup',
                'milestone_enddate' => '2024-09-30',
                'percentage' => 10,
                'amount' => 25000.00, // 10% of $250,000
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Requirements Analysis and Planning',
                'milestone_enddate' => '2024-06-15',
                'percentage' => 20,
                'amount' => 160000.00, // 20% of $800,000
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Software Design and Architecture',
                'milestone_enddate' => '2024-07-30',
                'percentage' => 20,
                'amount' => 160000.00, // 20% of $800,000
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Coding and Implementation',
                'milestone_enddate' => '2024-09-15',
                'percentage' => 20,
                'amount' => 160000.00, // 20% of $800,000
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Testing and Quality Assurance',
                'milestone_enddate' => '2024-10-30',
                'percentage' => 20,
                'amount' => 160000.00, // 20% of $800,000
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Software Design and Architecture',
                'milestone_enddate' => '2024-07-30',
                'percentage' => 20,
                'amount' => 500000.00, 
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Coding and Implementation',
                'milestone_enddate' => '2024-09-15',
                'percentage' => 20,
                'amount' => 1000000.00, 
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Testing and Quality Assurance',
                'milestone_enddate' => '2024-10-30',
                'percentage' => 20,
                'amount' => 1000000.00, 
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'Software Design and Architecture',
                'milestone_enddate' => '2024-07-30',
                'percentage' => 20,
                'amount' => 500000.00, 
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'Final delivery',
                'milestone_enddate' => '2024-09-15',
                'percentage' => 20,
                'amount' => 700000.00, 
            ],
            [
                'contract_id' => 10,
                'milestone_desc' => 'Software Design and Architecture',
                'milestone_enddate' => '2024-07-30',
                'percentage' => 20,
                'amount' => 100000.00, 
            ],
            [
                'contract_id' => 10,
                'milestone_desc' => 'Final delivery',
                'milestone_enddate' => '2024-09-15',
                'percentage' => 20,
                'amount' => 100000.00, 
            ],



        ];
        foreach ($dummydata_ff as $ffData) {
            $ffData = new FixedFeeContracts($ffData);
            $ffData->save();
    }
    return response()->json(['Data inserted']);
    }
}
