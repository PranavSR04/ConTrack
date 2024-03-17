<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListMsaTest extends TestCase
{
    /**
     * Test to check msa listing.
     */
    public function test_msa_list(): void
    {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/msa/list');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'msa_ref_id',
                    'added_by',
                    'client_name',
                    'region',
                    'start_date',
                    'end_date',
                    'comments',
                    'is_active',
                    'msa_doclink',
                    'created_at',
                    'updated_at',
                    'added_by_user',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active',
                ]
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
        
    }
}
