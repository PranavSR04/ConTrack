<?php

namespace App\ServiceInterfaces;
use Illuminate\Http\Request;

interface RevenueProjectionInterface
{
    public function revenueProjection(Request $request, $contract_id = null);
    public function calculateYearlyProjection($Milestones, $revenueProjections, $totalAmount);
    public function calculateQuarterlyProjection($Milestones, $revenueProjections, $totalAmount);
    public function calculateMonthlyProjection($Milestones, $revenueProjections, $totalAmount);
    public function getResponse($projectionType, $revenueProjections, $totalAmount);

}