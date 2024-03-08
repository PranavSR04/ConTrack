<?php

namespace App\ServiceInterfaces;

use Illuminate\Http\Request;

interface ContractInterface
{
    public function getContractData(Request $request, $id = null);
    public function addContract(Request $request);
}