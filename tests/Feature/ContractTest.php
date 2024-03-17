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
     * A basic feature test example.
     */
    public function test_get_active_hotel_by_id()
    {
        $contract_id = Contracts::where('contract_status', 'Active')->get()->random()->id;
        $response = $this->get('/api/contract/list/' . $contract_id)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    // 'code',
                    // 'message',
                    'data' => [
                        "id",
                        "msa_id",
                        "contract_added_by",
                        "contract_ref_id",
                        "contract_type",
                        "date_of_signature",
                        "comments",
                        "start_date",
                        "end_date",
                        "du",
                        "contract_doclink",
                        "estimated_amount",
                        "contract_status",
                        "created_at",
                        "updated_at",
                        "client_name",
                        "user_name",
                        "region",
                        "milestones"=> [
                            '*' => [
                                "id",
                                "contract_id",
                                "milestone_desc",
                                "milestone_enddate",
                                "percentage",
                                "amount",
                                "created_at",
                                "updated_at"
                            ], 
                        ],
                        "addendum"=> [
                            '*' => [
                                "id",
                                "contract_id",
                                "addendum_doclink",
                                "created_at",
                                "updated_at"
                        ], 
                    ],
                        "associated_users"=> [
                            '*' => [
                                "id",
                                "contract_id",
                                "user_name",
                                "user_mail"
                            ],
                        ],  
                ],
                ]
            );
}
}
