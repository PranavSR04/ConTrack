<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list_roles()
    {
        $this->withoutMiddleware();
        $response =$this->getJson('api/role/details');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    [
                            "id",
                            "role_name",
                            "role_access",
                            "is_active",
                            "created_at",
                            "updated_at"
        ]
    ]);
    }
}
