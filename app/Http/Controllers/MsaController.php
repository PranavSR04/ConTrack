<?php

namespace App\Http\Controllers;

use App\Models\ActivityLogs;
use App\Models\MSAs;
use App\Models\UserNotifications;
use App\ServiceInterfaces\MsaInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MsaController extends Controller
{
    protected $MsaService;
    public function insertValues()
    {
        $data = [
            [
                'msa_ref_id' => 'MSA001',
                'added_by' => 2,
                'client_name' => 'Microsoft Corporation',
                'region' => 'America',
                'start_date' => '2024-02-20',
                'end_date' => '2028-09-25',
                'comments' => 'Agreement for software licensing and support services.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1LfNFji6EACxqTdmHfYA5rQhG6x0rgK7z/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA002',
                'added_by' => 4,
                'client_name' => 'Apple Inc.',
                'region' => 'Europe',
                'start_date' => '2019-03-10',
                'end_date' => '2025-03-15',
                'comments' => 'Agreement for hardware supply and maintenance.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1hBNpEuy5bjC7DnY8wyzMTjYyR95HcDIF/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA003',
                'added_by' => 4,
                'client_name' => 'Amazon.com Inc.',
                'region' => 'Asia',
                'start_date' => '2020-04-05',
                'end_date' => '2024-01-30',
                'comments' => 'Agreement for cloud computing services.',
                'is_active' => false,
                'msa_doclink' => 'https://drive.google.com/file/d/1cQCug99b4KNDWxFhWJLiwyXHwr9bC5LB/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA004',
                'added_by' => 2,
                'client_name' => 'Alphabet Inc.',
                'region' => 'America',
                'start_date' => '2015-05-15',
                'end_date' => '2023-01-20',
                'comments' => 'Agreement for online advertising services.',
                'is_active' => false,
                'msa_doclink' => 'https://drive.google.com/file/d/1LfNFji6EACxqTdmHfYA5rQhG6x0rgK7z/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA005',
                'added_by' => 2,
                'client_name' => 'Facebook Inc.',
                'region' => 'Europe',
                'start_date' => '2024-01-20',
                'end_date' => '2029-12-25',
                'comments' => 'Agreement for social media platform access.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1cQCug99b4KNDWxFhWJLiwyXHwr9bC5LB/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA006',
                'added_by' => 4,
                'client_name' => 'Samsung Electronics Co., Ltd.',
                'region' => 'China',
                'start_date' => '2024-07-10',
                'end_date' => '2027-01-15',
                'comments' => 'Agreement for consumer electronics distribution.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1LfNFji6EACxqTdmHfYA5rQhG6x0rgK7z/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA007',
                'added_by' => 4,
                'client_name' => 'Walmart Inc.',
                'region' => 'America',
                'start_date' => '2021-08-05',
                'end_date' => '2025-10-10',
                'comments' => 'Agreement for retail supply chain management.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1cQCug99b4KNDWxFhWJLiwyXHwr9bC5LB/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA008',
                'added_by' => 4,
                'client_name' => 'Toyota Motor Corporation',
                'region' => 'India',
                'start_date' => '2024-01-25',
                'end_date' => '2030-03-30',
                'comments' => 'Agreement for automotive manufacturing collaboration.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1hBNpEuy5bjC7DnY8wyzMTjYyR95HcDIF/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA009',
                'added_by' => 4,
                'client_name' => 'Sony Corporation',
                'region' => 'Europe',
                'start_date' => '2020-10-10',
                'end_date' => '2025-04-15',
                'comments' => 'Agreement for entertainment content licensing.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1LfNFji6EACxqTdmHfYA5rQhG6x0rgK7z/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA010',
                'added_by' => 2,
                'client_name' => 'McDonald\'s Corporation',
                'region' => 'America',
                'start_date' => '2024-03-15',
                'end_date' => '2028-05-20',
                'comments' => 'Agreement for food supply and franchising.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1cQCug99b4KNDWxFhWJLiwyXHwr9bC5LB/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA011',
                'added_by' => 2,
                'client_name' => 'Tata Consultancy Services Ltd.',
                'region' => 'India',
                'start_date' => '2022-02-20',
                'end_date' => '2026-09-25',
                'comments' => 'Agreement for IT services outsourcing.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1hBNpEuy5bjC7DnY8wyzMTjYyR95HcDIF/view?usp=drive_link'
            ],
            [
                'msa_ref_id' => 'MSA012',
                'added_by' => 4,
                'client_name' => 'Reliance Industries Limited',
                'region' => 'India',
                'start_date' => '2023-03-10',
                'end_date' => '2029-12-15',
                'comments' => 'Agreement for oil and gas exploration.',
                'is_active' => true,
                'msa_doclink' => 'https://drive.google.com/file/d/1LfNFji6EACxqTdmHfYA5rQhG6x0rgK7z/view?usp=drive_link'
            ],
           
        ];


        foreach ($data as $msaData) {
            $msa = new MSAs($msaData);
            $msa->save();
        }

        return 'Values inserted';
    }

    /**
     * Retrieve a list of MSAs based on specified filters or sorting criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function __construct(MsaInterface $MsaService)
    {
        $this->MsaService=$MsaService;
    }
    /**
     * Add a new MSA (Master Service Agreement).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function MSAList(Request $request){
        return $this->MsaService->MSAList($request);

     }
    public function addMsa(Request $request,$user_id=null)
    {
        return $this->MsaService->addMsa($request,$user_id);

    }

    /**
     * Update an existing MSA.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMsa(Request $request,$user_id=null)
    {
        return $this->MsaService->updateMsa($request,$user_id);
    }

}
