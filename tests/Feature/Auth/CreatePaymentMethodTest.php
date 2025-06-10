<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class CreatePaymentMethodTest extends TestCase
{
    use RefreshDatabase; // Esto refrescará la base de datos para cada test

    #[Test]
    public function an_admin_can_create_a_payment_method()
    {
        // 1. Crear un usuario con rol 'admin' manualmente
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Asegurarse de que el usuario admin existe en la base de datos antes de autenticar
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // 2. Actuar como el usuario 'admin'
        // Usamos actingAs para iniciar sesión con el usuario creado
        $this->actingAs($admin);

        // 3. Datos para el nuevo método de pago
        $paymentMethodData = [
            'bank_name' => 'Banco de Prueba SA',
            'account_number' => 'ES1234567890123456789012',
        ];

        // 4. Enviar una solicitud POST directamente a la URL
        $response = $this->postJson('/payment-methods', $paymentMethodData);

        // 5. Verificar que la solicitud fue exitosa (código 201 Created)
        $response->assertStatus(201);

        // 6. Verificar que el método de pago fue guardado en la base de datos
        $this->assertDatabaseHas('payment_methods', $paymentMethodData);

        // 7. Opcional: Verificar la estructura de la respuesta JSON
        $response->assertJsonFragment([
            'bank_name' => 'Banco de Prueba SA',
            'account_number' => 'ES1234567890123456789012',
        ]);
    }

    #[Test]
    public function a_non_admin_cannot_create_a_payment_method()
    {
        // 1. Crear un usuario con un rol diferente a 'admin' manualmente
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper',
        ]);

        // Asegurarse de que el usuario regular existe en la base de datos antes de autenticar
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'role' => 'oper',
        ]);

        // 2. Actuar como el usuario no administrador
        $this->actingAs($regularUser);

        // 3. Datos para el nuevo método de pago
        $paymentMethodData = [
            'bank_name' => 'Banco Malicioso SL',
            'account_number' => 'ES9999999999999999999999',
        ];

        // 4. Enviar una solicitud POST directamente a la URL
        $response = $this->postJson('/payment-methods', $paymentMethodData);

        // 5. Verificar que la solicitud fue denegada (código 403 Forbidden)
        $response->assertStatus(403);

        // 6. Verificar que el método de pago NO fue guardado en la base de datos
        $this->assertDatabaseMissing('payment_methods', $paymentMethodData);
    }

    #[Test]
    public function guests_cannot_create_a_payment_method()
    {
        // No se autentica a ningún usuario, simulando un "guest"
        $paymentMethodData = [
            'bank_name' => 'Banco Anónimo',
            'account_number' => 'ES0000000000000000000000',
        ];

        // Enviar una solicitud POST directamente a la URL
        $response = $this->postJson('/payment-methods', $paymentMethodData);

        // Verificar que la solicitud fue no autorizada (código 401 Unauthorized)
        $response->assertStatus(401);

        // Verificar que el método de pago NO fue guardado en la base de datos
        $this->assertDatabaseMissing('payment_methods', $paymentMethodData);
    }

    #[Test]
    public function creating_a_payment_method_requires_bank_name_and_account_number()
    {
        // Crea un admin manualmente para este test de validación
        $admin = User::create([
            'name' => 'Validation Admin',
            'email' => 'validation@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Asegurarse de que el usuario admin existe en la base de datos antes de autenticar
        $this->assertDatabaseHas('users', [
            'email' => 'validation@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Intenta crear sin bank_name y account_number (o con uno solo)
        $response = $this->postJson('/payment-methods', []);

        // Verifica que la validación falló (código 422 Unprocessable Entity)
        $response->assertStatus(422);

        // Verifica que la respuesta JSON contenga los errores de validación para ambos campos
        $response->assertJsonValidationErrors(['bank_name', 'account_number']);

        // Intenta crear solo con bank_name
        $response = $this->postJson('/payment-methods', ['bank_name' => 'Un Banco']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['account_number']);

        // Intenta crear solo con account_number
        $response = $this->postJson('/payment-methods', ['account_number' => 'ES1234567890123456789012']);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['bank_name']);
    }
}
