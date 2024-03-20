<?php

namespace Tests\Feature;

use App\Models\Contracts;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractTest extends TestCase
{
    /**
     * A basic feature test for contract listing.
     */
    public function test_get_contractlist(){
        $this->withoutMiddleware();
        $response = $this->get('/api/contract/list/' )
            ->assertStatus(200)
            ->assertJsonStructure([
            
                'data' => [
                    '*' => [
                        'id',
                        'client_name',
                        'user_name',
                        'contract_type',
                        'date_of_signature',
                        'contract_ref_id',
                        'start_date',
                        'end_date',
                        'du',
                        'contract_status',
                    ],
                ],
                
            ]);
                
                    
}
//list individual contract of type ff
public function test_get_individual_contractlist(){
    $this->withoutMiddleware();
    $response = $this->get('/api/contract/list/1' )
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'client_name',
                    'user_name',
                    'contract_type',
                    'date_of_signature',
                    'contract_ref_id',
                    'start_date',
                    'end_date',
                    'du',
                    'contract_status',
                    'created_at',
                    'updated_at',
                    'milestones' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'milestone_desc',
                            'milestone_enddate',
                            'percentage',
                            'amount'
                        ],
                    ],
                    'addendum' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'addendum_doclink',
                        ],
                    ],
                    'associated_users' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'user_name',
                            'user_mail'
                        ],
                    ],
                ],
            ]
        ]);                        
}
//get individual contract error for invalid id
public function test_get_individual_contractlist_error(){
    $this->withoutMiddleware();
    $response = $this->get('/api/contract/list/0' )
        ->assertStatus(404)
     ->assertJson(['error' => 'Id not found in the database']);
                
}

//list individual contract of type TM
public function test_get_individual_contractlist_TM(){
    $this->withoutMiddleware();
    $response = $this->get('/api/contract/list/6' )
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'client_name',
                    'user_name',
                    'contract_type',
                    'date_of_signature',
                    'contract_ref_id',
                    'start_date',
                    'end_date',
                    'du',
                    'contract_status',
                    'created_at',
                    'updated_at',
                    'milestones' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'milestone_desc',
                            'milestone_enddate',
                            'amount'
                        ],
                    ],
                    'addendum' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'addendum_doclink',
                        ],
                    ],
                    'associated_users' => [
                        '*' => [
                            'id',
                            'contract_id',
                            'user_name',
                            'user_mail'
                        ],
                    ],
                ],
            ]
        ]);                        
}
//check contract counts by region
public function test_contract_counts_by_region()
{
    $this->withoutMiddleware();
    $response = $this->get('/api/contract/topRegions')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'region',
                    'contractCount',
                ],
            ],
        ]);
}
}
