<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\UserNotifications;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_notification_status(): void
    {
        $requestData=['user_id'=>2];
        UserNotifications::create([
            'log_id'=>9,
            'sendto_id' => $requestData['user_id'],
            'status' => 1,
        ]);
        $response = $this->putJson('/notification/statusupdate', $requestData);
        $response->assertTrue(200);
        $response->assertJson(['message' => 'updated']);
        $this->assertEquals(0, UserNotifications::where('sendto_id', $requestData['user_id'])->first()->status);
    }

}
