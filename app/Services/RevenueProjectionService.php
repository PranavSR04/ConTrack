<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Contracts;
use App\Models\MSAs;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use App\ServiceInterfaces\RevenueProjectionInterface;

class RevenueProjectionService implements RevenueProjectionInterface
{
    //Function to Retrive the Revenue Projection of Contracts
    public function revenueProjection(Request $request, $contract_id = null, $msa_id = null, )
    {

        $contracts = new Collection();
        $projectionType = $request->type;
        $revenueProjections = [];
        $totalAmount = 0;
        $duFilters = $request->du;
        $ctypeFilters = $request->ctype;

        if (strlen($request->startdate) === 4 && strlen($request->enddate) === 4) {
            $filterStartDate = (int) $request->startdate;
            $filterEndDate = (int) $request->enddate;
        } else if (strpos($request->startdate, 'Q')) {
            $filterStartDate = $request->startdate;
            $filterEndDate = $request->enddate;

        } else {
            $filterStartDate = $request->filled('startdate') ? Carbon::parse($request->startdate)->format('Y-m') : null;
            $filterEndDate = $request->filled('enddate') ? Carbon::parse($request->enddate)->format('Y-m') : null;
        }



        //Checks if the requested for individual contract revenue
        if ($contract_id == null && $request->msa_id ==null) {
            //checks for du and contract type filters
            if ($duFilters && $ctypeFilters) {
                foreach ($duFilters as $duFilter) {
                    foreach ($ctypeFilters as $ctypeFilter) {
                        $filteredContracts = Contracts::where('du', $duFilter)
                            ->where('contract_type', $ctypeFilter)
                            ->get();

                        $contracts = $contracts->merge($filteredContracts);
                    }
                }
            } elseif ($duFilters) {
                foreach ($duFilters as $duFilter) {
                    $filteredContracts = Contracts::where('du', $duFilter)->get();
                    $contracts = $contracts->merge($filteredContracts);
                }
            } elseif ($ctypeFilters) {
                foreach ($ctypeFilters as $ctypeFilter) {
                    $filteredContracts = Contracts::where('contract_type', $ctypeFilter)->get();
                    $contracts = $contracts->merge($filteredContracts);
                }
            } else {
                $contracts = Contracts::all();
                if ($contracts->isEmpty()) {
                    return response()->json(['error' => 'No contracts found'], 404);
                }


            }

            if ($contracts->isEmpty()) {
                return response()->json(['error' => 'No contracts found for the specified DU or Type'], 404);
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

            return $this->getResponse($projectionType, $revenueProjections, $totalAmount, $filterEndDate, $filterStartDate);
        } 
        else if($request->msa_id !==null){
            //get the msa ref id
            $msa_ref_id = MSAs::select('msa_ref_id')->where('id', $request->msa_id)->first();
             //Fetch all contracts that have the corresponding MSA ID
             $contracts = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
             ->where("msas.msa_ref_id", "=", $msa_ref_id)
             ->get();
             if ($duFilters && $ctypeFilters) {
                foreach ($duFilters as $duFilter) {
                    foreach ($ctypeFilters as $ctypeFilter) {
                        $filteredContracts = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                        ->where('du', $duFilter)
                            ->where('contract_type', $ctypeFilter)
                            ->where("msas.msa_ref_id", "=", $msa_ref_id)
                        ->get();

                        $contracts = $contracts->merge($filteredContracts);
                    }
                }
            } elseif ($duFilters) {
                foreach ($duFilters as $duFilter) {
                    $filteredContracts = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                    ->where('du', $duFilter)
                    ->where('msa_ref_id',$msa_ref_id )->get();
                    $contracts = $contracts->merge($filteredContracts);
                }
            } elseif ($ctypeFilters) {
                foreach ($ctypeFilters as $ctypeFilter) {
                    $filteredContracts = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                    ->where('contract_type', $ctypeFilter)
                    ->where('msa_ref_id',$msa_ref_id )->get();
                    $contracts = $contracts->merge($filteredContracts);
                }
            } else {
                $contracts = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                ->where('msa_ref_id',$msa_ref_id->msa_ref_id )->get();
                if ($contracts->isEmpty()) {
                    return response()->json(['error' => 'No contracts found'], 404);
                }
            }

            if ($contracts->isEmpty()) {
                return response()->json(['error' => 'No contracts found for the specified DU or Type'], 404);
            }
            foreach ($contracts as $contract) {
                if ($contract->contract_type === 'FF') {
                    $Milestones = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                    ->join("ff_contracts", "contracts.id", "=", "ff_contracts.contract_id")
                    ->where("msas.msa_ref_id", "=", $msa_ref_id->msa_ref_id )
                        ->where("contracts.id", "=", $contract->id)
                        ->select('ff_contracts.*')
                        ->get();
                } elseif ($contract->contract_type === 'TM') {
                    $Milestones = Contracts::join("msas", "msas.id", "=", "contracts.msa_id")
                        ->join("tm_contracts", "contracts.id", "=", "tm_contracts.contract_id")
                        ->where("msas.msa_ref_id", "=", $msa_ref_id->msa_ref_id )
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
            return $this->getResponse($projectionType, $revenueProjections, $totalAmount, $filterEndDate, $filterStartDate); 
        }
        else {
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

    // Yearly projection calculation logic
    public function calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y'); //formating date to year only format
            $totalAmount += $milestone->amount;
            if (!isset ($revenueProjections[$formattedDate])) {
                $revenueProjections[$formattedDate] = 0;
            }
            $revenueProjections[$formattedDate] += $milestone->amount;
        }
        return [$revenueProjections, $totalAmount];
    }


    // Quarterly projection calculation logic
    public function calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount)
    {
        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate);
            $quarter = ceil($formattedDate->month / 3);
            $key = $formattedDate->year . '-Q' . $quarter; //Creating an index for Quraters
            // Initialize the revenue projection for the quarter if it doesn't exist

            if (!isset ($revenueProjections[$key])) {
                $revenueProjections[$key] = 0;
            }
            $revenueProjections[$key] += $milestone->amount;
            $totalAmount += $milestone->amount;
        }

        return [$revenueProjections, $totalAmount];
    }

    // Monthly projection calculation logic
    public function calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount)
    {

        foreach ($Milestones as $milestone) {
            $formattedDate = Carbon::parse($milestone->milestone_enddate)->format('Y-m');
            $totalAmount += $milestone->amount;
            if (!isset ($revenueProjections[$formattedDate])) {
                $revenueProjections[$formattedDate] = 0;
            }
            $revenueProjections[$formattedDate] += $milestone->amount;
        }
        return [$revenueProjections, $totalAmount];
    }



    //Funtion for getting the Responce as required
    public function getResponse($projectionType, $revenueProjections, $totalAmount, $filterEndDate = null, $filterStartDate = null)
    {
        ksort($revenueProjections);
        if ($projectionType === 'yearly') {
            $revenueProjectionsFormatted = [];

            if ($filterStartDate && $filterEndDate) {

                foreach (array_keys($revenueProjections) as $key) {
                    $date = (int) $key;
                    if ($date >= $filterStartDate && $date <= $filterEndDate) {
                        $revenueProjectionsFormatted[$key] = $revenueProjections[$key];
                    }
                }
                if (!empty ($revenueProjectionsFormatted)) {
                    return response()->json([
                        'message' => "Yearly Revenue Projection ",
                        'data' => $revenueProjectionsFormatted,
                        'Total Revenue' => $totalAmount
                    ], 200);
                } else {
                    return response()->json(['error' => 'No contracts found for the specified Year'], 404);

                }


            } else {
                return response()->json([
                    'message' => "Yearly Revenue Projection ",
                    'data' => $revenueProjections,
                    'Total Revenue' => $totalAmount
                ], 200);
            }


        } elseif ($projectionType === 'quarterly') {
            $filteredProjections = [];

            if ($filterStartDate && $filterEndDate) {
                // Extract year and quarter from the start date
                $startParts = explode('-', $filterStartDate);
                $startYear = $startParts[0];
                $startQuarter = substr($startParts[1], 1); // Remove 'Q' from quarter

                // Extract year and quarter from the end date
                $endParts = explode('-', $filterEndDate);
                $endYear = $endParts[0];
                $endQuarter = substr($endParts[1], 1); // Remove 'Q' from quarter
                foreach ($revenueProjections as $key => $value) {
                    // Extract year and quarter from the key
                    $parts = explode('-', $key);
                    $year = $parts[0];
                    $quarter = substr($parts[1], 1); // Remove 'Q' from quarter

                    // Check if the year is within the range of start and end years
                    if ($year >= $startYear && $year <= $endYear) {
                        // Check if the quarter is within the range of start and end quarters
                        if (
                            ($year === $startYear && $quarter >= $startQuarter) ||
                            ($year === $endYear && $quarter <= $endQuarter) ||
                            ($year > $startYear && $year < $endYear)
                        ) {
                            // Key falls within the selected quarter range, include it in the response
                            $filteredProjections[$key] = $revenueProjections[$key];
                        }
                    }
                }
                if (!empty ($filteredProjections)) {
                    return response()->json([
                        'message' => "Quarterly Revenue Projection within the specified quarter range",
                        'data' => $filteredProjections,
                        'Total Revenue' => $totalAmount,
                    ], 200);
                } else {
                    return response()->json(['error' => 'No contracts found for the specified Quarter'], 404);

                }


            } else {
                return response()->json([
                    'message' => "Quarterly Revenue Projection ",
                    'data' => $revenueProjections,
                    'Total Revenue' => $totalAmount,

                ], 200);
            }
        } else {
            //Formating keys to desired formate
            $revenueProjectionsFormatted = [];
            if ($filterStartDate && $filterEndDate) {
                foreach (array_keys($revenueProjections) as $key) {
                    $date = Carbon::parse($key);

                    if ($date->gte($filterStartDate) && $date->lte($filterEndDate)) {
                        $formattedDate = Carbon::parse($key)->format('F, Y');
                        $revenueProjectionsFormatted[$formattedDate] = $revenueProjections[$key];
                    }
                }

            } else {
                foreach (array_keys($revenueProjections) as $key) {
                    $formattedDate = Carbon::parse($key)->format('F, Y');
                    $revenueProjectionsFormatted[$formattedDate] = $revenueProjections[$key];
                }
            }
            if (!empty ($revenueProjectionsFormatted)) {
                return response()->json([
                    'message' => "Monthly Revenue Projection ",
                    'data' => $revenueProjectionsFormatted,
                    'Total Revenue' => $totalAmount,

                ], 200);
            } else {
                return response()->json(['error' => 'No contracts found for the specified Month'], 404);

            }
        }
    }
}