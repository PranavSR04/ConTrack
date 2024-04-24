<?php

namespace App\Http\Controllers;
use App\ServiceInterfaces\RevenueProjectionInterface;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    protected $revenueProjectionService;

    public function __construct(RevenueProjectionInterface $revenueProjectionService)
    {
        $this->revenueProjectionService = $revenueProjectionService;
    }

    public function revenueProjections(Request $request, $contract_id = null, $msa_id = null){
       return $this->revenueProjectionService->revenueProjection($request, $contract_id, $msa_id);

    }


}





