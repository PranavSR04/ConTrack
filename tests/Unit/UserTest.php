<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\UserNotifications;

class NotificationStatusUpdateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_notification_statuscount(): void
    {
        $requestData=['user_id'=>2];
        $response = $this->putJson('PUT', '/notification/statusupdate', $requestData);
        $response->assertTrue(200);
        $response->assertJson(['message' => 'updated']);
        $this->assertEquals(0, UserNotifications::where('sendto_id', $requestData['user_id'])->first()->status);
    }

    public function test_notification_statuscouunt_usernotfound():void
    {
        $response = $this->putJson('/notification/statusupdate', [
            'user_id' => 999,
        ]);
        $response->assertStatus(404)->assertJson([
            'error' => [
                'sendto_id' => 'User ID not found.'
            ]
        ]);
    }
}
