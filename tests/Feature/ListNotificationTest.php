<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListNotificationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
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
        dump($response);
        $response->assertStatus(200);
       
    }
}

