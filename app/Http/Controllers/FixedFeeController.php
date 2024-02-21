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
                'amount' => 10000,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Prototype Development',
                'milestone_enddate' => '2024-06-30',
                'percentage' => 30,
                'amount' => 15000,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Prototype Testing and Feedback',
                'milestone_enddate' => '2024-08-15',
                'percentage' => 40,
                'amount' => 20000,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Manufacturing Setup',
                'milestone_enddate' => '2024-09-30',
                'percentage' => 10,
                'amount' => 25000,
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Market Research and Analysis',
                'milestone_enddate' => '2024-04-15',
                'percentage' => 10,
                'amount' => 6000,
            ],
            [
                'contract_id' => 2,
                'milestone_desc' => 'Product Packaging Design',
                'milestone_enddate' => '2024-06-01',
                'percentage' => 20,
                'amount' => 12000,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Marketing Strategy Development',
                'milestone_enddate' => '2024-07-10',
                'percentage' => 30,
                'amount' => 18000,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Product Launch',
                'milestone_enddate' => '2024-09-30',
                'percentage' => 40,
                'amount' => 22000,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Product Ideation',
                'milestone_enddate' => '2024-04-20',
                'percentage' => 15,
                'amount' => 8000,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Prototype Development',
                'milestone_enddate' => '2024-05-15',
                'percentage' => 30,
                'amount' => 15000,
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'User Testing and Feedback',
                'milestone_enddate' => '2024-06-30',
                'percentage' => 30,
                'amount' => 21000,
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'Final Product Refinement',
                'milestone_enddate' => '2024-08-15',
                'percentage' => 25,
                'amount' => 27000,
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'Product Research and Development',
                'milestone_enddate' => '2024-04-25',
                'percentage' => 10,
                'amount' => 9000,
            ],
            [
                'contract_id' => 5,
                'milestone_desc' => 'Product Testing and Quality Assurance',
                'milestone_enddate' => '2024-05-20',
                'percentage' => 20,
                'amount' => 18000,
            ],
            [
                'contract_id' => 4,
                'milestone_desc' => 'Product Packaging Design',
                'milestone_enddate' => '2024-07-10',
                'percentage' => 40,
                'amount' => 27000,
            ],
            [
                'contract_id' => 1,
                'milestone_desc' => 'Marketing Strategy Development',
                'milestone_enddate' => '2024-04-30',
                'percentage' => 30,
                'amount' => 36000,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Conceptualization and Planning',
                'milestone_enddate' => '2024-04-30',
                'percentage' => 25,
                'amount' => 10000,
            ],
            [
                'contract_id' => 3,
                'milestone_desc' => 'Product Design and Development',
                'milestone_enddate' => '2024-06-15',
                'percentage' => 50,
                'amount' => 20000,
            ],
            [
                'contract_id' => 6,
                'milestone_desc' => 'Prototype Testing and Feedback',
                'milestone_enddate' => '2024-08-01',
                'percentage' => 15,
                'amount' => 30000,
            ],
            [
                'contract_id' => 6,
                'milestone_desc' => 'Final Product Refinement',
                'milestone_enddate' => '2024-03-30',
                'percentage' => 10,
                'amount' => 40000,
            ],
        ];
        foreach ($dummydata_ff as $ffData) {
            $ffData = new FixedFeeContracts($ffData);
            $ffData->save();
    }
    }
}
