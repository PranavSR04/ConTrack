<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RenewMsaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();
    // Provide valid request parameters
    $validParams = [
        'msa_ref_id' => 'MSA361', // Provide an existing MSA reference ID
        'client_name' => 'Beta Solutions',
        'region' => 'Italy',
        'start_date' => '2024-03-18',
        'end_date' => '2027-03-18',
        'comments' => 'MSA Renewed',
        'file'=>'MSA Document'
        // Add other parameters as needed
    ];

    // Act
    $response = $this->post('/api/msa/renew/1', $validParams);

    // Assert
    $response->assertStatus(200);
    }
}
