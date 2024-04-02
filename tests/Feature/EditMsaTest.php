<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditMsaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/api/msa/update/1', [
            // Provide valid data for editing an MSA
            'msa_ref_id'=>'MSA949',
            'client_name' => 'Sun Solutions',
            'region' => 'Italy'
        ]);
    
        // Assert
        $response->assertStatus(200);
    }


public function test_edit_msa_invalid_date_range()
{
    // Arrange
    $this->withoutMiddleware();

    // Mock dependencies if needed
    $invalidStartDate = '2025-03-10';
    $invalidEndDate = '2025-03-05';
    // Act
    $response = $this->post('/api/msa/update/1', [
        // Provide data with an invalid date range
        'start_date' => $invalidStartDate,
        'end_date' => $invalidEndDate,
    ]);

    // Assert
    $response->assertStatus(400);
}

}
