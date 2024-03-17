<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    /**
     * Test to list user.
     */
    public function test_list_user()
    {
        $this->withoutMiddleware();

        $response = $this->getJson('/api/users/get?search=gokul');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);

        // $content = $response->decodeResponseJson();
        // dump($content);
        // return( $content);
    }
}
