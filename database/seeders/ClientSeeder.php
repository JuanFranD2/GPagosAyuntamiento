<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::insert([
            [
                'cif_nif' => '00000000T',
                'name' => 'Acme Corp S.L.',
                'address' => 'Calle Falsa, 123',
                'number' => null,
                'letter' => null,
                'staircase' => null,
                'phone' => '911234567',
                'town_municipality' => 'Madrid',
                'province' => 'Madrid',
                'postal_code' => '28001',
                'email' => 'info@acmecorp.es',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'cif_nif' => '11111111H',
                'name' => 'Global Services SA',
                'address' => 'Avenida Principal, 45',
                'number' => '2',
                'letter' => 'B',
                'staircase' => null,
                'phone' => '939876543',
                'town_municipality' => 'Barcelona',
                'province' => 'Barcelona',
                'postal_code' => '08002',
                'email' => 'sales@globalservices.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'cif_nif' => 'X1234567Z',
                'name' => 'Ana Pérez García',
                'address' => 'Plaza Mayor, 1',
                'number' => null,
                'letter' => null,
                'staircase' => null,
                'phone' => '612345678',
                'town_municipality' => 'Sevilla',
                'province' => 'Sevilla',
                'postal_code' => '41001',
                'email' => 'ana.perez@example.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more client data as needed
        ]);
    }
}
