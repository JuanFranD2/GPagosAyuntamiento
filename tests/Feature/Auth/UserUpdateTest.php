<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase; // Esto refrescará la base de datos para cada test

    #[Test]
    public function an_admin_can_update_a_user()
    {
        $admin = User::create([
            'name' => 'Update Admin',
            'email' => 'update.admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $userToUpdate = User::create([
            'name' => 'Original User',
            'email' => 'original@example.com',
            'password' => Hash::make('old_password'),
            'role' => 'oper',
        ]);

        $updatedData = [
            'name' => 'Updated User Name',
            'email' => 'updated@example.com',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
            'role' => 'admin',
        ];

        $response = $this->putJson('/users/' . $userToUpdate->id, $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated User Name',
            'email' => 'updated@example.com',
            'role' => 'admin',
        ]);

        $userAfterUpdate = User::find($userToUpdate->id);
        $this->assertTrue(Hash::check('new_password123', $userAfterUpdate->password));

        $response->assertJsonFragment([
            'name' => 'Updated User Name',
            'email' => 'updated@example.com',
            'role' => 'admin',
        ]);
    }

    #[Test]
    public function a_non_admin_cannot_update_a_user()
    {
        $oper = User::create([
            'name' => 'Oper Updater',
            'email' => 'oper.updater@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);
        $this->actingAs($oper);

        $userToUpdate = User::create([
            'name' => 'Target User',
            'email' => 'target@example.com',
            'password' => Hash::make('old_password'),
            'role' => 'oper',
        ]);

        $updatedData = [
            'name' => 'Attempted Update',
            'email' => 'attempted@example.com',
            'role' => 'admin',
        ];

        $response = $this->putJson('/users/' . $userToUpdate->id, $updatedData);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Target User',
            'email' => 'target@example.com',
            'role' => 'oper',
        ]);
    }

    #[Test]
    public function cannot_update_non_existent_user()
    {
        $admin = User::create([
            'name' => 'Admin NonExistent',
            'email' => 'admin.nonexistent@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $nonExistentUserId = 9999;

        $updatedData = [
            'name' => 'Non Existent Name',
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'oper',
        ];

        $response = $this->putJson('/users/' . $nonExistentUserId, $updatedData);

        $response->assertStatus(404);
    }

    #[Test]
    public function updating_a_user_requires_valid_data()
    {
        $admin = User::create([
            'name' => 'Admin Update Validation',
            'email' => 'admin.upd.val@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $userToUpdate = User::create([
            'name' => 'User To Validate',
            'email' => 'user.to.val@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);

        // Test 1: Email inválido
        $response = $this->putJson('/users/' . $userToUpdate->id, [
            'name' => 'Valid Name',
            'email' => 'invalid-email',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // Test 2: Rol inválido
        $response = $this->putJson('/users/' . $userToUpdate->id, [
            'name' => 'Valid Name',
            'email' => 'valid@example.com',
            'role' => 'bad_role',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role']);

        // Test 3: Contraseña demasiado corta
        $response = $this->putJson('/users/' . $userToUpdate->id, [
            'name' => 'Valid Name',
            'email' => 'valid@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // Test 4: Email ya existe (y no es el del propio usuario)
        User::create([
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);
        $response = $this->putJson('/users/' . $userToUpdate->id, [
            'name' => 'Valid Name',
            'email' => 'another@example.com',
            'role' => 'oper',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
