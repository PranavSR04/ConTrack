<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddContractTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->postJson('/api/contracts/add',[
            $Contractdata=[
                'msa_id'=>111,
                'contract_ref_id' =>'A111',
                'contract_type' => 'TM',
                'start_date' => '2024-03-17',
                'end_date' => '2025-03-17',
                'date_of_signature' => '2024-03-16',
                'du' => 'DU1',
                'estimated_amount' => 10000,
                'comments' => 'This is a sample comment.',
            ],
            // $data = [
            //     'msa_id' => 1, // Assuming MSA ID 1 exists in the database
            //     'contract_ref_id' => 'CONTRACT1234567890', // Maximum 25 characters
            //     'contract_type' => 'Service', // Maximum 25 characters
            //     'start_date' => '2024-03-17', // Date format, before end date and after date of signature
            //     'end_date' => '2025-03-17', // Date format, after start date
            //     'date_of_signature' => '2024-03-16', // Date format
            //     'du' => 'Department A', // Dummy department name
            //     'estimated_amount' => 10000.50, // Numeric value, minimum 0
            //     'comments' => 'This is a sample comment.', // Optional string
            //     'file' => $fileObject, // File upload
            //     'associated_users' => [
            //         ['user_id' => 1], // Assuming user with ID 1 exists in the database
            //         ['user_id' => 2]  // Assuming user with ID 2 exists in the database
            //     ]
            // ];
            
        ]);

        $response->assertStatus(200);
    }
}
