<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface ContractInterface
{
    public function getContractData(Request $request, $id = null);
    public function updateContractData(Request $request, $contractId) ;
    public function addContract(Request $request);

    public function getDuCount(Request $request);
    
    public function getAllContractsRevenue();
}