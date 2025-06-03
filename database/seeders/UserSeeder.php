<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'UsuarioAdmin',
                'email' => 'admin@hinojos.es',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'UsuarioOper',
                'email' => 'oper@hinojos.es',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more user data as needed
        ]);
    }
}
