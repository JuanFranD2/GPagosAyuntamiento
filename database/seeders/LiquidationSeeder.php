<?php

namespace Database\Seeders;

use App\Models\Liquidation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LiquidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Liquidation::insert([
            [
                'file_number' => '19/2025',
                'liquidation_date' => Carbon::parse('2024-12-15'),
                'concept' => 'Impuesto sobre Sociedades 2023',
                'taxable_base' => 100000.00,
                'tax_rate' => 0.25,
                'total_fee' => 25000.00,
                'bond' => null,
                'total_to_pay' => 25000.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'file_number' => '898/2026',
                'liquidation_date' => Carbon::parse('2025-03-20'),
                'concept' => 'IVA Trimestre 1 - 2025',
                'taxable_base' => 50000.00,
                'tax_rate' => 0.21,
                'total_fee' => 10500.00,
                'bond' => 1000.00,
                'total_to_pay' => 9500.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'file_number' => '56/789',
                'liquidation_date' => Carbon::parse('2025-06-10'),
                'concept' => 'IRPF Mensual - Mayo 2025',
                'taxable_base' => 3000.00,
                'tax_rate' => 0.15,
                'total_fee' => 450.00,
                'bond' => null,
                'total_to_pay' => 450.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more liquidation data as needed
        ]);
    }
}
