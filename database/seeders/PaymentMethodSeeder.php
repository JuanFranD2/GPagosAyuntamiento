<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            [
                'bank_name' => 'CAJA RURAL',
                'account_number' => 'ES454555454544545',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'CAIXABANK',
                'account_number' => 'ES898989898989898', // Ejemplo de número de tarjeta (no guardar datos reales así)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'AYUNTAMIENTO',
                'account_number' => 'TARJETA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
