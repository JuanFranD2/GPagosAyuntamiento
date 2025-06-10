<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserCreateTest extends TestCase
{
    use RefreshDatabase; // Esto refrescará la base de datos para cada test

    #[Test]
    public function an_admin_can_create_a_user()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $userData = [
            'name' => 'New Oper User',
            'email' => 'new.oper@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ];

        $response = $this->postJson('/users', $userData);

        // ¡CAMBIO AQUÍ! Esperamos 200 OK, no 201 Created
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'New Oper User',
            'email' => 'new.oper@example.com',
            'role' => 'oper',
        ]);
        $response->assertJsonFragment([
            'name' => 'New Oper User',
            'email' => 'new.oper@example.com',
            'role' => 'oper',
        ]);
    }

    #[Test]
    public function a_non_admin_cannot_create_a_user()
    {
        $oper = User::create([
            'name' => 'Oper User',
            'email' => 'oper@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);
        $this->actingAs($oper);

        $userData = [
            'name' => 'Unauthorized User',
            'email' => 'unauth@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ];

        $response = $this->postJson('/users', $userData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'unauth@example.com']);
    }

    #[Test]
    public function guests_cannot_create_a_user()
    {
        $userData = [
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ];

        $response = $this->postJson('/users', $userData);

        $response->assertStatus(401);
        $this->assertDatabaseMissing('users', ['email' => 'guest@example.com']);
    }

    #[Test]
    public function creating_a_user_requires_valid_data()
    {
        $admin = User::create([
            'name' => 'Admin For Validation',
            'email' => 'admin.val@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        // Test 1: Datos incompletos
        $response = $this->postJson('/users', [
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);

        // Test 2: Email inválido
        $response = $this->postJson('/users', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // Test 3: Contraseña no coincide
        $response = $this->postJson('/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // Test 4: Rol inválido
        $response = $this->postJson('/users', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'unsupported_role',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role']);

        // Test 5: Email ya existe
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);
        $response = $this->postJson('/users', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
