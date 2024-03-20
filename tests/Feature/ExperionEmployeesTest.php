<?php

namespace Tests\Feature;

use App\Models\ExperionEmployees;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExperionEmployeesTest extends TestCase
{
    /**
     * Test to check if experion employees are listed
     */
    public function test_show_endpoint_returns_users_matching_name()
    {
        $this->withoutMiddleware();

        // Creating a test user
        $user = ExperionEmployees::create([
            'email_id' => "sarah.thomas@experionglobal.com",
            "password" => "sarahmary",
            'first_name' => 'Sarah',
            'middle_name' => 'Mary',
            'last_name' => 'Thomas',
        ]);

        // Making a request to the endpoint with the name "Sarah"
        $response = $this->getJson('api/experion/list?name=sarah');

        // Asserting that the response status is 200 OK
        $response->assertStatus(200);

        // Asserting that the response contains the user data
        $response->assertJsonFragment([
            [
                'id' => $user->id,
                'email_id' => $user->email_id,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);

    }

    public function test_returns_not_found_if_no_users_matching_name()
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/experion/list?name=NonExistingName');

        $response->assertStatus(404)
            ->assertJson(['error' => 'No records found.']);
    }

    public function test_validates_name_parameter()
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/experion/list');

        $response->assertStatus(422)
            ->assertJsonStructure([
                'error' => [
                    'name',
                ],
            ]);

    }

}
