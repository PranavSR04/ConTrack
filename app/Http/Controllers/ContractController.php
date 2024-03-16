<?php

namespace App\Http\Controllers;

use App\Models\Addendums;
use App\Models\AssociatedUsers;
use App\Models\Contracts;
use App\Models\FixedFeeContracts;
use App\Models\TimeAndMaterialContracts;
use App\ServiceInterfaces\ContractInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    private $contractService;
    public function __construct(ContractInterface $contractService)
    {
        $this->contractService = $contractService;
    }

    
    /**
     * Retrieve contract data based on the provided parameters.
     *
     * If an ID is provided, it fetches individual contract details along with associated milestones, addendums,
     * and associated users. If no ID is provided, it retrieves a list of contracts based on the request parameters.
     *
     * @param \Illuminate\Http\Request $request
     * @param int|null $id (optional) The ID of the contract to retrieve individual details.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception if an error occurs during data retrieval.
     */
    public function getContractData(Request $request, $id = null)
    {
        return $this->contractService->getContractData($request, $id);
    }

    public function getContractCount(Request $request)
    {
        return $this->contractService->getContractCount($request);
    }

    /**
     * Function to update a contract.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing updated contract data.
     * @param int $contractId The ID of the contract to be updated.
     * @return \Illuminate\Http\JsonResponse|string JSON response indicating the status of the update or an error message.
     */
    public function updateContractData(Request $request, $contractId)
    {
        return $this->contractService->updateContractData($request, $contractId);
    }

    public function addContract(Request $request)
    {
        return $this->contractService->addContract($request);
    }

    public function getAllContractsRevenue()
    {
        return $this->contractService->getAllContractsRevenue();
    }
    public function getTopContractRegions() {
        return $this->contractService->getTopContractRegions();
    }


}