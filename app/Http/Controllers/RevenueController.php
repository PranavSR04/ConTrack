<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contracts;
use Carbon\Carbon;
use Exception;

class RevenueController extends Controller
{
    //Function to Retrive the Revenue Projection of Contracts 
    public function revenueProjection(Request $request, $contract_id = null)
    {
        $projectionType = $request->type;
        $revenueProjections = [];
        $totalAmount = 0;
        $duFilter = $request->du;

        if ($contract_id === null) {

            if ($duFilter) {
                $contracts = Contracts::where('du', $duFilter)->get();
                if ($contracts->isEmpty()) {
                    return response()->json(['error' => 'No contracts found for the specified DU'], 404);
                }
            } else {
                $contracts = Contracts::all();
                if ($contracts->isEmpty()) {
                    return response()->json(['error' => 'No contracts found'], 404);
                }
                // return response()->json([$contracts]);

            }

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
        // Yearly projection calculation logic
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y'); //formating date to year only format
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
            $quarter = ceil($formattedDate->month / 3);            
            $key = $formattedDate->year . '-Q' . $quarter; //Creating an index for Quraters
            // Initialize the revenue projection for the quarter if it doesn't exist
            if (!isset($revenueProjections[$key])) {
                $revenueProjections[$key] = 0;
            }
            $revenueProjections[$key] += $milestone->amount;
            $totalAmount += $milestone->amount;
        }

        return [$revenueProjections, $totalAmount];
    }

    private function calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        // Monthly projection calculation logic
        foreach ($Milestones as $milestone) {
            // $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('F, Y');
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y-m');
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
        ksort($revenueProjections);
        if ($projectionType === 'yearly') {

            return response()->json([
                'message' => "Yearly Revenue Projection ",
                'data' => $revenueProjections,
                'Total Revenue' => $totalAmount
            ], 200);
        } elseif ($projectionType === 'quarterly') {
            return response()->json([
                'message' => "Quarterly Revenue Projection ",
                'data' => $revenueProjections,
                'Total Revenue' => $totalAmount,

            ], 200);
        } else {
            //Formating keys to desired formate
            foreach (array_keys($revenueProjections) as $key) {
                $formattedDate = Carbon::parse($key)->format('F, Y');
                $revenueProjectionsFormatted[$formattedDate] = $revenueProjections[$key];
            }


            return response()->json([
                'message' => "Monthly Revenue Projection ",
                'data' => $revenueProjectionsFormatted,
                'Total Revenue' => $totalAmount,

            ], 200);
        }
    }



}





