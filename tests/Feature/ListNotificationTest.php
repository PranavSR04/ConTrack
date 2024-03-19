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
    public function test_get(): void
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/notification/list?sendto_id=1');
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
    public function test_validate(): void
    {
        $this->withoutMiddleware();
        $page='hello';
        $response = $this->getJson('/api/notification/list',[
            'page'=>$page
        ]);
     
           
        $response->assertStatus(422);
       
    }

    public function test_userinvalid(): void
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
    public function test_user_noNotification():void{
        $this->withoutMiddleware();
        $response = $this->getJson('/api/notification/list?sendto_id=7');
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'No notifications found.'
        ]);
    }
}

