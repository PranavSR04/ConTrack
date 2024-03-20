<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddContractTest extends TestCase
{
    public function test_example(): void
    {
        $response = $this->postJson('/api/contracts/add', [
            $Contractdata = [
                'msa_id' => 111,
                'contract_ref_id' => 'A111',
                'contract_type' => 'TM',
                'start_date' => '2024-03-17',
                'end_date' => '2025-03-17',
                'date_of_signature' => '2024-03-16',
                'du' => 'DU1',
                'estimated_amount' => 10000,
                'comments' => 'This is a sample comment.',
            ],

        ]);

        $response->assertStatus(200);
    }
}

