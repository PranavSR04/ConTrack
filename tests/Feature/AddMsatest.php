<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddMsatest extends TestCase
{
    public function test_example(): void{
    $msa=MSAs::create([
        'msa_ref_id'=>rand(),
        'client_name'=>rand(),
        'region' => rand(),
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now(),
                'comments' => rand(),
                'file' => rand(),
                'is_active'=> rand(),
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
    ]);
        $user=USERs::create([
        'experion_id'=>rand(),
        'role_id'=>rand(),
        'user_name'=>rand(),
        'user_mail'=>rand(),
        'user_designation'=>rand(),
        'group_name'=>rand(),
        'is_active'=>rand()
        ]);
    $payload=[
        'msa_ref_id'=>$msa_ref_id,
        'client_name'=>$client_name,
        'region' => $region,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'comments' => $comments,
                'file' => $file,
                'user_id'=>$user_id

    ]
    {
        $response = $this->JSON('POST','/api/msa/add',$payload);

        $response->assertStatus(200)->assertJson([
            "code"=>"401",
            "message"=>"Msa is added"
        ]);
    }
}
} 
