<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDuCountTest extends TestCase
{
    /**
     * A test to check if du count is listed properly.
     */
    public function test_to_get_du_count(): void
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/contract/ducount');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'duCounts',
                'totalContractsCount'
            ]);

    }

    // public function test_unknowncolumn_error_ducount()
    // {
    //     $this->withoutMiddleware();
    //     // Mock a query error by changing the table name
    //     $response = $this->json('GET', '/api/contract/ducount');

    //     // Assert the response status code and content
    //     $response->assertStatus(500)
    //         ->assertJson([
    //             'error' => 'Database error: Column not found',
    //         ]);
    // }
}
