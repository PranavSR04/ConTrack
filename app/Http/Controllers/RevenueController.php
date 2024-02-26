<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services;
use Illuminate\Http\Request;
use App\Models\Contracts;
use Carbon\Carbon;
use Exception;

class RevenueController extends Controller
{
    //Function to Retrive the Revenue Projection of Contracts 
    // public function revenueProjections(Request $request, $contract_id = null)
    // {
    //     //Checks if the contract id Null then ,Lists all the Revenue Projections
    //     //Else shows revenue projection for a parfticular contract
    //     $projectionType = $request->type;

    //     if ($contract_id === null) {

    //         // Initialize an empty array to store revenue projections and Total Amount
    //         $revenueProjections = [];
    //         $totalAmount = 0;

    //         // Retrieve all contracts
    //         $contracts = Contracts::all();
    //         // return response()->json([$this->getRevenueProjections($contracts, $projectionType)]);

    //         // Loop through each contract
    //         foreach ($contracts as $contract) {
    //             // Check contract type
    //             if ($contract->contract_type === 'FF') {
    //                 // Retrieve revenue projections for Fixed Fee contracts
    //                 $Milestones = Contracts::join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
    //                     ->where("contracts.id", "=", $contract->id)
    //                     ->select('ff_contracts.*')
    //                     ->get();
    //             } elseif ($contract->contract_type === 'TandM') {
    //                 // Retrieve revenue projections for Time & Material contracts
    //                 $Milestones = Contracts::join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
    //                     ->where("contracts.id", "=", $contract->id)
    //                     ->select('tm_contracts.*')
    //                     ->get();
    //             }


    //             if ($projectionType === 'yearly') {

    //                 foreach ($Milestones as $milestone) {
    //                     $totalAmount += $milestone->amount;
    //                     $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y');

    //                     // Initialize the revenue projection for the year if it doesn't exist
    //                     if (!isset($revenueProjections[$formattedDate])) {
    //                         $revenueProjections[$formattedDate] = 0;
    //                     }
    //                     $revenueProjections[$formattedDate] += $milestone->amount;
    //                 }



    //             } elseif ($projectionType === 'quarterly') {
    //                 // $revenueProjection = $this->calculateQuarterlyProjection($Milestones);
    //                 foreach ($Milestones as $milestone) {
    //                     $formattedDate = Carbon::parse($milestone->milestone_enddate);

    //                     // Get the quarter of the year for the milestone date
    //                     $quarter = ceil($formattedDate->month / 3);

    //                     // Generate a key in the format 'year-Q#'
    //                     $key = $formattedDate->year . '-Q' . $quarter;

    //                     // Initialize the revenue projection for the quarter if it doesn't exist
    //                     if (!isset($revenueProjections[$key])) {
    //                         $revenueProjections[$key] = 0;
    //                     }

    //                     // Add the milestone amount to the revenue projection for the quarter
    //                     $revenueProjections[$key] += $milestone->amount;
    //                     $totalAmount += $milestone->amount;
    //                 }

    //                 // Ensure all quarters are present in the result
    //                 for ($i = 1; $i <= 4; $i++) {
    //                     $quarterKey = now()->year . '-Q' . $i;
    //                     if (!isset($revenueProjections[$quarterKey])) {
    //                         $revenueProjections[$quarterKey] = 0;
    //                     }
    //                 }


    //             } else {

    //                 foreach ($Milestones as $milestone) {
    //                     $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('F d, Y');
    //                     $totalAmount += $milestone->amount;
    //                     if (!isset($revenueProjections[$formattedDate])) {
    //                         $revenueProjections[$formattedDate] = 0;
    //                     }

    //                     $revenueProjections[$formattedDate] += $milestone->amount;
    //                 }
    //             }



    //         }

    //         // Return revenue projections for all contracts
    //         if ($projectionType === 'yearly') {
    //             return response()->json(["Yearly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
    //         } elseif ($projectionType === 'quarterly') {
    //             return response()->json(["Quarterly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
    //         } else {
    //             return response()->json(["Monthly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
    //         }
    //     } else {
    //         try {
    //             $contract = Contracts::findOrFail($contract_id);
    //         } catch (Exception $e) {
    //             return response()->json(["Error" => "Contract Not Found"], 404);
    //         }
    //         $revenueProjections = [];

    //         if ($contract['contract_type'] === 'FF') { //if type is Fixed fee then retrieves the pyment details from Fixedfee table
    //             $contractJoined = Contracts::join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
    //                 ->where("contracts.id", "=", $contract_id)
    //                 ->select('ff_contracts.*')
    //                 ->get();

    //             foreach ($contractJoined as $milestones) {
    //                 $formattedDate = Carbon::parse($milestones['milestone_enddate'])->format('F d, Y');
    //                 $revenueProjections[$formattedDate] = $milestones['amount'];
    //             }
    //         } elseif ($contract['contract_type'] === 'TandM') { //if type is Time & Material then retrieves the pyment details from Fixedfee table
    //             $contractJoined = Contracts::join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
    //                 ->where("contracts.id", "=", $contract_id)
    //                 ->select('tm_contracts.*')
    //                 ->get();

    //             foreach ($contractJoined as $milestones) {
    //                 $formattedDate = Carbon::parse($milestones['milestone_enddate'])->format('F d, Y');
    //                 $revenueProjections[$formattedDate] = $milestones['amount'];
    //             }
    //         }
    //         return response()->json([$revenueProjections], 200);
    //     }
    // }


    public function revenueProjection(Request $request, $contract_id = null)
    {
        $projectionType = $request->type;
        $revenueProjections = [];
        $totalAmount = 0;

        if ($contract_id === null) {

            $contracts = Contracts::all();

            foreach ($contracts as $contract) {
                if ($contract->contract_type === 'FF') {
                    $Milestones = Contracts::join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
                        ->where("contracts.id", "=", $contract->id)
                        ->select('ff_contracts.*')
                        ->get();
                } elseif ($contract->contract_type === 'TM') {
                    $Milestones = Contracts::join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
                        ->where("contracts.id", "=", $contract->id)
                        ->select('tm_contracts.*')
                        ->get();
                }

                if ($projectionType === 'yearly') {
                    list($revenueProjections, $totalAmount) = $this->calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount);
                } elseif ($projectionType === 'quarterly') {
                    list($revenueProjections, $totalAmount) = $this->calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount);
                } else {
                    list($revenueProjections, $totalAmount) = $this->calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount);
                }
            }

            return $this->getResponse($projectionType, $revenueProjections, $totalAmount);
        } else {
            // Code for fetching revenue projections for a specific contract
            try {
                $contract = Contracts::findOrFail($contract_id);
            } catch (Exception $e) {
                return response()->json(["Error" => "Contract Not Found"], 404);
            }
            $revenueProjections = [];

            if ($contract['contract_type'] === 'FF') { //if type is Fixed fee then retrieves the pyment details from Fixedfee table
                $Milestones = Contracts::join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
                    ->where("contracts.id", "=", $contract_id)
                    ->select('ff_contracts.*')
                    ->get();

            } elseif ($contract['contract_type'] === 'TM') { //if type is Time & Material then retrieves the pyment details from Fixedfee table
                $Milestones = Contracts::join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
                    ->where("contracts.id", "=", $contract_id)
                    ->select('tm_contracts.*')
                    ->get();
            }

            if ($projectionType === 'yearly') {
                list($revenueProjections, $totalAmount) = $this->calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount);
            } elseif ($projectionType === 'quarterly') {
                list($revenueProjections, $totalAmount) = $this->calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount);
            } else {
                list($revenueProjections, $totalAmount) = $this->calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount);
            }
            return $this->getResponse($projectionType, $revenueProjections, $totalAmount);

        }
    }

    private function calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y');
            $totalAmount += $milestone->amount;

            if (!isset($revenueProjections[$formattedDate])) {
                $revenueProjections[$formattedDate] = 0;
            }

            $revenueProjections[$formattedDate] += $milestone->amount;
        }

        return [$revenueProjections, $totalAmount];
    }

    private function calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        // Quarterly projection calculation logic
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate);

            // Get the quarter of the year for the milestone date
            $quarter = ceil($formattedDate->month / 3);

            // Generate a key in the format 'year-Q#'
            $key = $formattedDate->year . '-Q' . $quarter;

            // Initialize the revenue projection for the quarter if it doesn't exist
            if (!isset($revenueProjections[$key])) {
                $revenueProjections[$key] = 0;
            }

            // Add the milestone amount to the revenue projection for the quarter
            $revenueProjections[$key] += $milestone->amount;
            $totalAmount += $milestone->amount;
        }

        // Ensure all quarters are present in the result
        for ($i = 1; $i <= 4; $i++) {
            $quarterKey = now()->year . '-Q' . $i;
            if (!isset($revenueProjections[$quarterKey])) {
                $revenueProjections[$quarterKey] = 0;
            }
        }

        return [$revenueProjections, $totalAmount];
    }

    private function calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        // Monthly projection calculation logic
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('F d, Y');
            $totalAmount += $milestone->amount;

            if (!isset($revenueProjections[$formattedDate])) {
                $revenueProjections[$formattedDate] = 0;
            }

            $revenueProjections[$formattedDate] += $milestone->amount;
        }

        return [$revenueProjections, $totalAmount];
    }

    private function getResponse($projectionType, $revenueProjections, $totalAmount)
    {
        if ($projectionType === 'yearly') {
            return response()->json(["Yearly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
        } elseif ($projectionType === 'quarterly') {
            return response()->json(["Quarterly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
        } else {
            return response()->json(["Monthly Revenue Projection " => $revenueProjections, "Total Revenue" => $totalAmount], 200);
        }
    }



    // private function getRevenueProjections($contracts, $projectionType)
    // {
    //     foreach ($contracts as $contract) {
    //         // return response()->json([$contracts]);
    //         // Check contract type
    //         if ($contract->contract_type === 'FF') {
    //             // Retrieve revenue projections for Fixed Fee contracts
    //             $Milestones = Contracts::join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
    //                 ->where("contracts.id", "=", $contract->id)
    //                 ->select('ff_contracts.*')
    //                 ->get();
    //         } elseif ($contract->contract_type === 'TandM') {
    //             // Retrieve revenue projections for Time & Material contracts
    //             $Milestones = Contracts::join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
    //                 ->where("contracts.id", "=", $contract->id)
    //                 ->select('tm_contracts.*')
    //                 ->get();
    //         }

    //         // Calculate total revenue for each contract and group by month
    //         foreach ($Milestones as $milestone) {
    //             $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('F d, Y');
    //             if (!isset($revenueProjections[$formattedDate])) {
    //                 $revenueProjections[$formattedDate] = 0;
    //             }
    //             // $totalAmount += $milestone->amount;
    //             $revenueProjections[$formattedDate] += $milestone->amount;


    //             if ($projectionType === 'yearly') {
    //                 $revenueProjection = $this->calculateYearlyProjection($Milestones);
    //                 return $revenueProjections;
    //             } elseif ($projectionType === 'quarterly') {
    //                 $revenueProjection = $this->calculateQuarterlyProjection($Milestones);
    //             } else {
    //                 $revenueProjection = $this->calculateMonthlyProjection($Milestones);
    //             }
    //         }

    //     }
    //     return $revenueProjection;
    // }

    // private function calculateYearlyProjection($Milestones)
    // {
    //     // Calculate yearly revenue projection here
    //     // return response()->json([$Milestones]);

    //     // Loop through each milestone to calculate revenue projections for each year
    //     foreach ($Milestones as $milestone) {

    //         $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y');

    //         // Initialize the revenue projection for the year if it doesn't exist
    //         if (!isset($revenueProjections[$formattedDate])) {
    //             $revenueProjections[$formattedDate] = 0;
    //         }
    //         $revenueProjections[$formattedDate] += $milestone->amount;


    //         // Add the milestone amount to the revenue projection for the year

    //     }

    //     return $revenueProjections;
    // }

    // // private function calculateYearlyProjection($Milestones)
    // // {
    // //     // Calculate yearly revenue projection here
    // //     $revenueProjections = [];
    // //     foreach ($Milestones as $milestone) {
    // //         $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y');
    // //         echo $formattedDate;
    // //         if (!isset($revenueProjections[$formattedDate])) {
    // //             $revenueProjections[$formattedDate] = 0;
    // //         }
    // //         // $totalAmount += $milestone->amount;
    // //         $revenueProjections[$formattedDate] += $milestone->amount;
    // //     }
    // //     return $revenueProjections;
    // // }

    // private function calculateQuarterlyProjection($Milestones)
    // {
    //     // Initialize an array to hold quarterly revenue projections
    //     $revenueProjections = [];

    //     // Loop through the milestones to calculate the revenue for each quarter
    //     foreach ($Milestones as $milestone) {
    //         $formattedDate = Carbon::parse($milestone->milestone_enddate);

    //         // Get the quarter of the year for the milestone date
    //         $quarter = ceil($formattedDate->month / 3);

    //         // Generate a key in the format 'year-Q#'
    //         $key = $formattedDate->year . '-Q' . $quarter;

    //         // Initialize the revenue projection for the quarter if it doesn't exist
    //         if (!isset($revenueProjections[$key])) {
    //             $revenueProjections[$key] = 0;
    //         }

    //         // Add the milestone amount to the revenue projection for the quarter
    //         $revenueProjections[$key] += $milestone->amount;
    //     }

    //     // Ensure all quarters are present in the result
    //     for ($i = 1; $i <= 4; $i++) {
    //         $quarterKey = now()->year . '-Q' . $i;
    //         if (!isset($revenueProjections[$quarterKey])) {
    //             $revenueProjections[$quarterKey] = 0;
    //         }
    //     }

    //     return $revenueProjections;
    // }


    // private function calculateMonthlyProjection($Milestones)
    // {
    //     // Calculate monthly revenue projection here
    //     $revenueProjections = [];
    //     foreach ($Milestones as $milestone) {
    //         $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('F,Y');
    //         if (!isset($revenueProjections[$formattedDate])) {
    //             $revenueProjections[$formattedDate] = 0;
    //         }
    //         // $totalAmount += $milestone->amount;
    //         $revenueProjections[$formattedDate] += $milestone->amount;
    //     }
    //     return $revenueProjections;
    // }
}





