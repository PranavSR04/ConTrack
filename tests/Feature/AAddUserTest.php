<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class AAddUserTest extends TestCase
{
    /**
     * Test to check if user is being added correctly.
     */
    public function test_add_user(): void
    {
        $this->withoutMiddleware();

        // Create a mock experion employee
        $experionEmployeeId = 10; 
        $experionEmployeeData = [
            'id' => $experionEmployeeId,
            'first_name' => 'Sarah',
            'middle_name' => 'Mary',
            'last_name' => 'Thomas',
            'email_id' => 'sarah.thomas@experionglobal.com',
        ];

        // Mock the ExperionEmployees model to return the mock experion employee
        $mockExperionEmployee = \Mockery::mock('alias:App\Models\ExperionEmployees');
        $mockExperionEmployee->shouldReceive('where')->with('id', $experionEmployeeId)->andReturnSelf();
        $mockExperionEmployee->shouldReceive('first')->andReturn((object) $experionEmployeeData);

        // Make a request to the endpoint to add a user
        $response = $this->postJson('/api/users/add', [
            'experion_id' => $experionEmployeeId,
            'role_id' => 2, 
            'designation' => 'DU2 Head', 
        ]);

        // Assert response contains the success message
        $response->assertJson(['message' => 'User added successfully'], 201);

        // Assert that the user is created in the database
        $this->assertDatabaseHas('users', [
            'experion_id' => $experionEmployeeId,
            'role_id' => 2,
            'user_name' => 'Sarah Mary Thomas',
            'user_mail' => 'sarah.thomas@experionglobal.com',
            'is_active' => 1,
            'user_designation' => 'DU2 Head',
        ]);
    }
}

