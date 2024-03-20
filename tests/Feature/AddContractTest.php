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
        $this->withoutMiddleware();

        $response = $this->postJson('/api/contracts/add',[
        [
            'msa_id' => 'MSA200',
                'contract_added_by' => 1,
                'contract_ref_id' => 'ASD345',
                'contract_type' => 'TM',
                'start_date' => '2023-09-09',
                'end_date' => '2025-09-09',
                'date_of_signature' => '2022-09-09',
                'du' => 'DU1',
                'contract_status' => "Active",
                'estimated_amount' => 100,
                'comments' => 'HIGH',
                'file'=>'CONTRACT PDF',
        ]
           
             ]);

        $response->assertStatus(200);
    }
    public function test_add_contract_invalid_amount()
{

    // Arrange
    $this->withoutMiddleware();

    $contract_type='FF';
    $totalAmount = 70 ;
    $estimated_amount=90;
    // Act
    $response = $this->post('/api/contracts/add/1', [
        
    'contract_type'=>$contract_type,
    'totalAmount' =>$totalAmount,
    'estimated_amount'=>$estimated_amount
    ]);

    // Assert
    $response->assertStatus(404);
}
public function test_add_contract_invalid_amount_tm()
{
    // Arrange
    $this->withoutMiddleware();

    $contract_type='TM';
    $totalAmount = 70 ;
    $estimated_amount=90;
    // Act
    $response = $this->post('/api/contracts/add/1', [
        
    'contract_type'=>$contract_type,
    'totalAmount' =>$totalAmount,
    'estimated_amount'=>$estimated_amount
    ]);

    // Assert
    $response->assertStatus(404);
}
}

