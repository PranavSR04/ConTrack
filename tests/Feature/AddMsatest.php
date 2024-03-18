<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddMsatest extends TestCase
{
    public function test_add_msa(): void
    {
        $this->withoutMiddleware();

        $file = \Illuminate\Http\Testing\File::create('C:\Users\athul.nair\Downloads\Online Gantt 20240313.pdf', 'MSAfile.txt');

        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data',
        ])->post('/api/msa/add/1', [
            'msa_ref_id' => 'MSA30',
            'client_name' => 'Sun Solutions',
            'region' => 'Italy',
            'start_date' => '2024-03-15',
            'end_date' => '2025-03-31',
            'comments' => 'Msa Added',
            'file' => $file,
        ]);
        

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'MSA created successfully',
                'msa' => [
                    'msa_ref_id',
                    'added_by',
                    'client_name',
                    'region',
                    'start_date',
                    'end_date',
                    'msa_doclink',
                    'comments',
                    'updated_at',
                    'created_at',
                    'id',
                    'added_by_user',
                ],
            ]);

    }
}
