<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RevenueProjectionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {   
        $this->withoutMiddleware();
        $response = $this->getJson('/api/revenue/list?type=quarterly');
        $response2 = $this->getJson('/api/revenue/list?type=monthly');
        $response->assertJson([
            'data' => [
                '2022-Q2' => 6000,
                '2023-Q1' => 4000,
                '2023-Q2' => 2900,
                '2023-Q3' => 3125,
                '2024-Q1' => 8700,
                '2024-Q2' => 3125,
                '2024-Q3' => 14000,
                '2024-Q4' => 36750,
                '2025-Q1' => 4000,
                '2025-Q2' => 16000,
                '2025-Q3' => 16000,
                '2025-Q4' => 23400,
                '2026-Q1' => 20500,
                '2026-Q2' => 16000,
                '2026-Q3' => 21000,
                '2026-Q4' => 8000,
                '2027-Q1' => 24500,
                '2027-Q2' => 5000,
                '2027-Q4' => 13000,
                '2028-Q2' => 7500,
                '2029-Q1' => 13000
            ],
            'Total Revenue' => 266500
        ]);
        $response2->assertJson([
            'data' => [
                'May, 2022' => 6000,
                'March, 2023' => 4000,
                'May, 2023' => 2900,
                'September, 2023' => 3125,
                'February, 2024' => 8700,
                'April, 2024' => 3125,
                'August, 2024' => 14000,
                'October, 2024' => 11000,
                'November, 2024' => 4000,
                'December, 2024' => 21750,
                'March, 2025' => 4000,
                'April, 2025' => 5000,
                'May, 2025' => 4000,
                'June, 2025' => 7000,
                'July, 2025' => 6000,
                'September, 2025' => 10000,
                'October, 2025' => 6000,
                'November, 2025' => 17400,
                'February, 2026' => 5000,
                'March, 2026' => 15500,
                'May, 2026' => 16000,
                'July, 2026' => 10000,
                'September, 2026' => 11000,
                'December, 2026' => 8000,
                'January, 2027' => 12000,
                'March, 2027' => 12500,
                'May, 2027' => 5000,
                'November, 2027' => 13000,
                'May, 2028' => 7500,
                'March, 2029' => 13000
            ],
            'Total Revenue' => 266500
        ]);
        $response->assertStatus(200);
        $response2->assertStatus(200);
    }
}
