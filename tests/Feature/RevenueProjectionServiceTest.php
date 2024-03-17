<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Contracts;
use App\Services\RevenueProjectionService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevenueProjectionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $revenueProjectionService;

    public function setUp(): void
    {
        parent::setUp();
        $this->revenueProjectionService = new RevenueProjectionService();
    }

    /** @test */
    public function it_returns_error_if_no_contracts_found()
    {
        $request = new Request();
        $response = $this->revenueProjectionService->revenueProjection($request);
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('error', json_decode($response->getContent(), true));
    }

    // Add more test cases to cover other scenarios...
}

