<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RevenueProjectionTest extends TestCase
{

    public function test_monthly_revenge_projection(): void {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/revenue/list?type=monthly');
        $response->assertJson([
            'message' => 'Monthly Revenue Projection ',
            'data' => [
                'May, 2022' => 600000,
                'July, 2022' => 800000,
                'August, 2022' => 800000,
                'March, 2023' => 400000,
                'May, 2023' => 290000,
                'June, 2023' => 2100000,
                'September, 2023' => 312500,
                'October, 2023' => 800000,
                'November, 2023' => 2300000,
                'January, 2024' => 870000,
                'February, 2024' => 500000,
                'March, 2024' => 1000000,
                'April, 2024' => 800000,
                'May, 2024' => 1300000,
                'August, 2024' => 400000,
                'September, 2024' => 312500,
                'October, 2024' => 1100000,
                'November, 2024' => 400000,
                'December, 2024' => 2175000,
                'March, 2025' => 400000,
                'April, 2025' => 500000,
                'May, 2025' => 400000,
                'June, 2025' => 700000,
                'July, 2025' => 600000,
                'September, 2025' => 1000000,
                'October, 2025' => 600000,
                'November, 2025' => 1740000,
                'February, 2026' => 500000,
                'March, 2026' => 1550000,
                'May, 2026' => 1600000,
                'July, 2026' => 1000000,
                'September, 2026' => 1100000,
                'December, 2026' => 800000,
                'January, 2027' => 1200000,
                'March, 2027' => 1250000,
                'May, 2027' => 500000,
                'November, 2027' => 1300000,
                'May, 2028' => 750000,
                'March, 2029' => 1300000
            ],
            'Total Revenue' => 36050000
        ]);
        
        $response->assertStatus(200);


    }
    public function test_quarterly_revenge_projection(): void {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/revenue/list?type=quarterly');
        $response->assertJson([
            'message' => 'Quarterly Revenue Projection ',
            'data' => [
                '2022-Q2' => 600000,
                '2022-Q3' => 1600000,
                '2023-Q1' => 400000,
                '2023-Q2' => 2390000,
                '2023-Q3' => 312500,
                '2023-Q4' => 3100000,
                '2024-Q1' => 2370000,
                '2024-Q2' => 2100000,
                '2024-Q3' => 712500,
                '2024-Q4' => 3675000,
                '2025-Q1' => 400000,
                '2025-Q2' => 1600000,
                '2025-Q3' => 1600000,
                '2025-Q4' => 2340000,
                '2026-Q1' => 2050000,
                '2026-Q2' => 1600000,
                '2026-Q3' => 2100000,
                '2026-Q4' => 800000,
                '2027-Q1' => 2450000,
                '2027-Q2' => 500000,
                '2027-Q4' => 1300000,
                '2028-Q2' => 750000,
                '2029-Q1' => 1300000
            ],
            'Total Revenue' => 36050000
        ]);
        



        $response->assertStatus(200);


    }
    public function test_yearly_revenge_projection(): void {
        $this->withoutMiddleware();
        $response = $this->getJson('/api/revenue/list?type=yearly');
        $response->assertJson([
            'message' => 'Yearly Revenue Projection ',
            'data' => [
                '2022' => 2200000,
                '2023' => 6202500,
                '2024' => 8857500,
                '2025' => 5940000,
                '2026' => 6550000,
                '2027' => 4250000,
                '2028' => 750000,
                '2029' => 1300000
            ],
            'Total Revenue' => 36050000
        ]);
        



        $response->assertStatus(200);


    }
    
}
