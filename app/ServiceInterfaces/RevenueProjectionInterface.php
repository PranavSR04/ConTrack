<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface RevenueProjectionInterface
{
    // Function for calculating the revenue projection overall
    public function revenueProjection(Request $request, $contract_id = null);

    // Function for calculating yearly projection
    public function calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount);

    // Function for calculating quarterly projection
    public function calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount);

    // Function for calculating monthly projection
    public function calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount);

    // Function for getting the final response 
    public function getResponse($projectionType, $revenueProjections, $totalAmount);

}