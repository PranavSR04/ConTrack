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
    public function insertContractsData()
    {
        $contractsDataArray = [
            [
                'contract_ref_id' => 'AGF7',
                'msa_id' => 1,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->addMonths(2),
                'comments' => " view document to see further milestone data",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(36),
                'du' => 'DU1',
                'estimated_amount' => 15000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'A166',
                'msa_id' => 2,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(10),
                'comments' => "Fixed fee with tight schedule",
                'start_date' => now()->subMonths(9),
                'end_date' => now()->addMonths(10),
                'du' => 'DU2',
                'estimated_amount' => 12500.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()

            ],
            [
                'contract_ref_id' => 'ABC1',
                'msa_id' => 1,
                'contract_added_by' => 4,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->addMonths(12),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(13),
                'end_date' => now()->addMonths(36),
                'du' => 'DU3',
                'estimated_amount' => 15000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
           
            [
                'contract_ref_id' => 'AN21',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->addMonths(7),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(8),
                'end_date' => now()->addMonths(48),
                'du' => 'DU4',
                'estimated_amount' => 30000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'N621',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(10),
                'comments' => "File also available in drive",
                'start_date' =>  now()->addMonths(12),
                'end_date' => now()->addMonths(48),
                'du' => 'DU1',
                'estimated_amount' => 24000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'A091',
                'msa_id' => 6,
                'contract_added_by' => 4,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(6),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(7),
                'end_date' => now()->addMonths(29),
                'du' => 'DU2',
                'estimated_amount' => 40000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1yJf_Ras4QgDxCQGOA_AEJpwK3Z1aZohp/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'M921',
                'msa_id' => 8,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(2),
                'comments' => "Contact me if it requires further change",
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(63),
                'du' => 'DU3',
                'estimated_amount' => 45000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB1',
                'msa_id' => 10,
                'contract_added_by' => 4,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(39),
                'du' => 'DU4',
                'estimated_amount' => 14000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1yJf_Ras4QgDxCQGOA_AEJpwK3Z1aZohp/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB2',
                'msa_id' => 12,
                'contract_added_by' => 2,
                'contract_type' => "TM",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(64),
                'du' => 'DU1',
                'estimated_amount' => 15000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1vb6H0Qc6m7YFqJ6nZ_7Ra6h8SWzbOu8h/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB3',
                'msa_id' => 10,
                'contract_added_by' => 4,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->addMonths(1),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(4),
                'end_date' => now()->addMonths(40),
                'du' => 'DU2',
                'estimated_amount' => 16000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB4',
                'msa_id' => 11,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(13),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(12),
                'end_date' => now()->addMonths(24),
                'du' => 'DU3',
                'estimated_amount' =>29000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB5',
                'msa_id' => 5,
                'contract_added_by' => 2,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->addMonths(4),
                'comments' => "High priority, complete on time",
                'start_date' => now()->addMonths(5),
                'end_date' => now()->addMonths(65),
                'du' => 'DU4',
                'estimated_amount' => 30000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1l0972E5hKPnSCWwBmkpudqLAsU3y5iw_/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                'contract_ref_id' => 'ABB6',
                'msa_id' => 9,
                'contract_added_by' => 4,
                'contract_type' => "Fixed Fee",
                'date_of_signature' => now()->subMonths(25),
                'comments' => "view document to see further milestone data",
                'start_date' => now()->subMonths(24),
                'end_date' => now()->addMonths(4),
                'du' => 'DU1',
                'estimated_amount' => 20000.00,
                'contract_doclink' => "https://drive.google.com/file/d/1s5547VlMkoachZprsufLs2eWIAPNsbZG/view?usp=drive_link",
                'contract_status' => 'Active',
                "created_at" => now(),
                "updated_at" => now()
            ],
            
        ];

        foreach ($contractsDataArray as $contractData) {
            $contractsData = new Contracts($contractData);
            $contractsData->save();
        }
        
        return response()->json(['Data inserted']);
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