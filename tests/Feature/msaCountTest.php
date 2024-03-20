<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class msaCountTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/contract/ducount');
        $response->assertJsonStructure([
            'duCounts',
            'totalContractsCount'
        ]);
        $response->assertStatus(200);
    }
}
