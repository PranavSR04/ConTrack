<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;

class EditUserTest extends TestCase
{
    /**
     * A  test to check edit user functionality.
     */
    public function test_edit_user(): void
    {
        $this->withoutMiddleware();
        
        // Selecting a user to update
        $user = User::where('experion_id', 1)->first();
        // Ensure that the user exists
        if (!$user) {
            throw new \Exception('User not found in the database');
        }

        // Make a request to the endpoint to update user role
        $response = $this->putJson("/api/users/update/{$user->id}", [
            'role_id' => 3, 
        ]);

        // Assert response contains the success message
        $response->assertJson(['message' => 'User updated successfully']);

        // Refresh the user instance from the database
        $user->refresh();

        // Assert that the user's role is updated
        $this->assertEquals(3, $user->role_id);

        $response->assertStatus(200);
    }

    /**
     * Test soft deleting user successfully.
     *
     * @return void
     */
    public function testSoftDeleteUser()
    {
        $this->withoutMiddleware();
        
        // Selecting a user to update
        $user = User::where('experion_id', 1)->first();
        // Ensure that the user exists
        if (!$user) {
            throw new \Exception('User not found in the database');
        }

        // Make a request to the endpoint to soft delete user
        $response = $this->putJson("/api/users/update/{$user->id}", [
            'is_active' => false,
        ]);

        // Assert response contains the success message
        $response->assertJson(['message' => 'User soft deleted successfully']);

        // Refresh the user instance from the database
        $user->refresh();

        // Assert that the user is soft deleted (is_active is set to 0)
        $this->assertEquals(0, $user->is_active);
    }

}
