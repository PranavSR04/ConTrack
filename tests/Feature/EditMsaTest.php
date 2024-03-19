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
public function test_edit_msa_database_error()
{
    // Arrange
    $this->withoutMiddleware();

    // Mock dependencies if needed
    $invalidData = [
        // Provide valid data
        'client_name' => 'Example Client',
        'region' => 'Example Region',
        'start_date' => '2025-03-01',
        'end_date' => '2025-03-31',
        // Add other fields as needed
    ];
    // Act
    $response = $this->post('/api/msa/update/1', $invalidData);

    // Assert
    $response->assertStatus(500);
}
}
