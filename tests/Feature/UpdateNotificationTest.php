<?php

namespace Tests\Feature;

use App\Models\UserNotifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateNotificationTest extends TestCase
{
    public function test_notification_statusupdate_api_works(): void
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

    public function test_it_returns_unprocessable_entity_if_validation_fails()
    {
        $user_id='Demo';
        $response = $this->postJson('/api/notification/statusupdate', ['user_id' => $user_id]);

        $response->assertStatus(405);
    }
}


