<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();
        $response = $this->putJson('/api/notification/statusupdate?user_id=5', [
            'status' => 0,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('user_notifications', [
            'sendto_id' => 5,
           'status'=>0
        ]);
        $response->assertStatus(200);
    }
}


