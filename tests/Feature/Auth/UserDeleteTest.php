<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase; // Esto refrescará la base de datos para cada test

    #[Test]
    public function an_admin_can_delete_a_user()
    {
        $admin = User::create([
            'name' => 'Delete Admin',
            'email' => 'delete.admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $userToDelete = User::create([
            'name' => 'User To Be Deleted',
            'email' => 'to.delete@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);

        $response = $this->deleteJson('/users/' . $userToDelete->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
        $response->assertJson(['message' => 'Usuario eliminado correctamente.']);
    }

    #[Test]
    public function a_non_admin_cannot_delete_a_user()
    {
        $oper = User::create([
            'name' => 'Oper Deleter',
            'email' => 'oper.deleter@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);
        $this->actingAs($oper);

        $userToDelete = User::create([
            'name' => 'Target User Delete',
            'email' => 'target.delete@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);

        $response = $this->deleteJson('/users/' . $userToDelete->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $userToDelete->id]);
    }

    #[Test]
    public function cannot_delete_non_existent_user()
    {
        $admin = User::create([
            'name' => 'Admin NonExistent Delete',
            'email' => 'admin.nonexistent.del@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin);

        $nonExistentUserId = 9999;

        $response = $this->deleteJson('/users/' . $nonExistentUserId);

        $response->assertStatus(404);
    }
}
