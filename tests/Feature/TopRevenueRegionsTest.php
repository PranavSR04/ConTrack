<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopRevenueRegionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_to_get_topRevenueRegions(): void
    {
        $this->withoutMiddleware();
        $response = $this->json('GET', '/api/contracts/topRevenueRegions');

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
            '*' => [
                'region',
                'total_amount'
            ]
        ]);

    }
}
