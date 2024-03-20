<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface ContractInterface
{
    public function getContractData(Request $request, $id = null);

    /**
     * Function to update a contract.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing updated contract data.
     * @param int $contractId The ID of the contract to be updated.
     * @return \Illuminate\Http\JsonResponse|string JSON response indicating the status of the update or an error message.
     */
    public function updateContractData(Request $request, $contractId) ;
    public function addContract(Request $request);
    public function getContractCount();

    public function getDuCount(Request $request);
    
    public function getAllContractsRevenue();
    public function topRevenueRegions();
    public function getTopContractRegions();
}