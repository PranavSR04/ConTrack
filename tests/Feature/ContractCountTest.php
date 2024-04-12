<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractCountTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();

        $response = $this->get('/api/contract/count');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                "total",
                "active",
                "progress",
                "expiring",
                "closed",
                "Expired",
            ]
        ]);
    }
    public function testDBError()
    {
        $this->withoutMiddleware();

        $response = $this->get('/api/contract/count');

        $response->assertStatus(200);
    }
}
