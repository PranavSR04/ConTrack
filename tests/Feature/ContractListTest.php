<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractListTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/contract/list?1');
        // dump($response);
        $response->assertJsonStructure([
            'data',]);
                
        $response->assertStatus(200);
    }
}
