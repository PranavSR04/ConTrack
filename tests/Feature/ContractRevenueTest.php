<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractRevenueTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {        
        $this->withoutMiddleware();

        $response = $this->get('/api/contracts/revenue');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'contract_id',
                'duration_months',
                'estimated_amount',
            ]
        ]);
    }
}
