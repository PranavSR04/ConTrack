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
            'msa_ref_id' => 'MSA099',
            'client_name' => 'Sun Solutions',
            'region' => 'Italy',
            'start_date' => '2024-03-15',
            'end_date' => '2025-03-31',
            'comments' => 'Msa Added',
            'file' => $file,
        ]);
        

        $response->assertStatus(200);

    }
}
