<?php

namespace App\Http\Controllers;

use App\Models\MSAs;
use Illuminate\Http\Request;

class MsaController extends Controller
{
    public function insertValues(){
        $data = [
            [
                'msa_id' => 'MSA001',
                'added_by'=>1,
                'client_name' => 'Microsoft Corporation',
                'region' => 'America',
                'start_date' => '2024-02-20',
                'end_date' => '2024-09-25',
                'comments' => 'Agreement for software licensing and support services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa001-document'
            ],
            [
                'msa_id' => 'MSA002',
                'added_by'=>1,
                'client_name' => 'Apple Inc.',
                'region' => 'Europe',
                'start_date' => '2019-03-10',
                'end_date' => '2020-03-15',
                'comments' => 'Agreement for hardware supply and maintenance.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa002-document'
            ],
            [
                'msa_id' => 'MSA003',             
                'added_by'=>5,
                'client_name' => 'Amazon.com Inc.',
                'region' => 'Asia',
                'start_date' => '2020-04-05',
                'end_date' => '2024-11-30',
                'comments' => 'Agreement for cloud computing services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa003-document'
            ],
            [
                'msa_id' => 'MSA004',
                'added_by'=>2,
                'client_name' => 'Alphabet Inc.',
                'region' => 'America',
                'start_date' => '2015-05-15',
                'end_date' => '2024-10-20',
                'comments' => 'Agreement for online advertising services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa004-document'
            ],
            [
                'msa_id' => 'MSA005',
                'added_by'=>2,
                'client_name' => 'Facebook Inc.',
                'region' => 'Europe',
                'start_date' => '2018-06-20',
                'end_date' => '2024-12-25',
                'comments' => 'Agreement for social media platform access.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa005-document'
            ],
            [
                'msa_id' => 'MSA006',
                'added_by'=>2,
                'client_name' => 'Samsung Electronics Co., Ltd.',
                'region' => 'China',
                'start_date' => '2023-07-10',
                'end_date' => '2024-01-15',
                'comments' => 'Agreement for consumer electronics distribution.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa006-document'
            ],
            [
                'msa_id' => 'MSA007',
                'added_by'=>5,
                'client_name' => 'Walmart Inc.',
                'region' => 'America',
                'start_date' => '2021-08-05',
                'end_date' => '2024-10-10',
                'comments' => 'Agreement for retail supply chain management.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa007-document'
            ],
            [
                'msa_id' => 'MSA008',
                'added_by'=>3,
                'client_name' => 'Toyota Motor Corporation',
                'region' => 'India',
                'start_date' => '2020-09-25',
                'end_date' => '2025-03-30',
                'comments' => 'Agreement for automotive manufacturing collaboration.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa008-document'
            ],
            [
                'msa_id' => 9,
                'added_by'=>'US004',
                'client_name' => 'Sony Corporation',
                'region' => 'Europe',
                'start_date' => '2020-10-10',
                'end_date' => '2025-04-15',
                'comments' => 'Agreement for entertainment content licensing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa009-document'
            ],
            [
                'msa_id' => 'MSA010',
                'added_by'=>3,
                'client_name' => 'McDonald\'s Corporation',
                'region' => 'America',
                'start_date' => '2024-01-15',
                'end_date' => '2025-05-20',
                'comments' => 'Agreement for food supply and franchising.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa010-document'
            ],
            [
                'msa_id' => 'MSA011',
                'added_by'=>1,
                'client_name' => 'Tata Consultancy Services Ltd.',
                'region' => 'India',
                'start_date' => '2019-02-20',
                'end_date' => '2020-09-25',
                'comments' => 'Agreement for IT services outsourcing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa011-document'
            ],
            [
                'msa_id' => 'MSA012',
                'added_by'=>3,
                'client_name' => 'Reliance Industries Limited',
                'region' => 'India',
                'start_date' => '2019-03-10',
                'end_date' => '2024-12-15',
                'comments' => 'Agreement for oil and gas exploration.',
                'is_active' => false,
                'msa_doclink' => 'https://example.com/msa012-document'
            ],
            [
                'msa_id' => 'MSA013',
                'added_by'=>5,
                'client_name' => 'Infosys Limited',
                'region' => 'India',
                'start_date' => '2015-04-05',
                'end_date' => '2025-11-30',
                'comments' => 'Agreement for software development services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa013-document'
            ],
            [
                'msa_id' => 'MSA014',
                 'added_by'=>2,
                'client_name' => 'HDFC Bank Limited',
                'region' => 'India',
                'start_date' => '2024-01-15',
                'end_date' => '2024-10-20',
                'comments' => 'Agreement for banking and financial services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa014-document'
            ],
            [
                'msa_id' => 'MSA015',
                'added_by'=>5,
                'client_name' => 'Mahindra & Mahindra Limited',
                'region' => 'India',
                'start_date' => '2020-06-20',
                'end_date' => '2024-12-25',
                'comments' => 'Agreement for automobile manufacturing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa015-document'
            ],
            [
                'msa_id' => 'MSA016',
                'added_by'=>2,
                'client_name' => 'State Bank of India',
                'region' => 'India',
                'start_date' => '2014-07-10',
                'end_date' => '2026-11-15',
                'comments' => 'Agreement for banking and financial services.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa016-document'
            ],
            [
                'msa_id' => 'MSA017',
                'added_by'=>4,
                'client_name' => 'Bharat Petroleum Corporation Limited',
                'region' => 'India',
                'start_date' => '2020-08-05',
                'end_date' => '2030-10-10',
                'comments' => 'Agreement for oil and gas refining.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa017-document'
            ],
            [
                'msa_id' => 'MSA018',
                'added_by'=>2,
                'client_name' => 'Wipro Limited',
                'region' => 'India',
                'start_date' => '2024-02-25',
                'end_date' => '2025-03-30',
                'comments' => 'Agreement for IT services and consulting.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa018-document'
            ],
            [
                'msa_id' => 'MSA019',
                'added_by'=>1,
                'client_name' => 'Indian Oil Corporation Limited',
                'region' => 'India',
                'start_date' => '2024-10-10',
                'end_date' => '2025-04-15',
                'comments' => 'Agreement for oil and gas refining.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa019-document'
            ],
            [
                'msa_id' => 'MSA020',
                'added_by'=>2,
                'client_name' => 'Bajaj Auto Limited',
                'region' => 'India',
                'start_date' => '2024-01-15',
                'end_date' => '2025-05-20',
                'comments' => 'Agreement for motorcycle manufacturing.',
                'is_active' => true,
                'msa_doclink' => 'https://example.com/msa020-document'
            ]
        ];

 
    foreach ($data as $msaData) {
        $msa = new MSAs($msaData);
        $msa->save();
    }

    return 'Values inserted';
  }
/**
 * Retrieve a list of all MSAs.
 *
 * Fetches all MSAs from the database and returns them as a JSON response.
 *
 * @return \Illuminate\Http\JsonResponse
 */
 public function MSAList()
{
    $msas = MSAs::all();

    return response()->json($msas);
}
}
