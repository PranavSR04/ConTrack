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
        $response =$this->getJson('/api/role/details');
        dump($response);

        $response->assertStatus(200);
    }
}
