<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class ListNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_notificationlist_api_get(): void
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/notification/list?sendto_id=5');
        $response->assertJsonStructure([
            'data' => [
                'total_notifications',
                'current_page',
                'notifications_per_page',
                'active_notifications_count',
                'NotificationListdisplay' => [
                    '*' => [
                        'log_id',
                        'contract_ref_id',
                        'contract_id',
                        'msa_ref_id',
                        'msa_id',
                        'client_name',
                        'performed_by',
                        'action',
                        'updated_at',
                    ],
                ],
            ],
        ]);
        $response->assertStatus(200);
       
    }
    public function test_it_returns_unprocessable_entity_if_validation_fails(): void
    {
        $this->withoutMiddleware();
        $page='hello';
        $response = $this->getJson('/api/notification/list',[
            'page'=>$page
        ]);
        $response->assertStatus(422);
       
    }

    public function test_invalidUserId(): void
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/notification/list?sendto_id=999999');
        $response->assertJson([
            "error" => [
                "sendto_id" => "User ID not found."
            ]
        ]);
        $response->assertStatus(404);
    }
    public function test_validUserId_NoNotificationsFound():void{
        $this->withoutMiddleware();
        $response = $this->getJson('/api/notification/list?sendto_id=6');
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'No notifications found.'
        ]);
    }
}

