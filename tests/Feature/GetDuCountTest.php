<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDuCountTest extends TestCase
{
   
    public function test_to_get__api_ducount(): void
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/contract/ducount');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'duCounts',
                'totalContractsCount'
            ]);

    }
}
