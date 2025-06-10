<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\PaymentMethod;
use App\Models\User;

class UpdatePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin puede actualizar un método de pago.
     *
     * @return void
     */
    public function test_admin_can_update_a_payment_method()
    {
        $this->withoutExceptionHandling();
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        // Crear una instancia de PaymentMethod manualmente
        $paymentMethod = new PaymentMethod([
            'bank_name' => 'Banco Original',
            'account_number' => 'ES0000000000000000000000',
        ]);
        $paymentMethod->save(); // Guardar en la base de datos

        $updatedData = [
            'bank_name' => 'Banco Actualizado S.A.',
            'account_number' => 'ES9999999999999999999999',
        ];

        $response = $this->put('/payment-methods/' . $paymentMethod->id, $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $paymentMethod->id,
                'bank_name' => 'Banco Actualizado S.A.',
                'account_number' => 'ES9999999999999999999999',
            ]);

        // Verificar que la base de datos se ha actualizado
        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
            'bank_name' => 'Banco Actualizado S.A.',
            'account_number' => 'ES9999999999999999999999',
        ]);

        // Verificar que los datos originales ya no existen
        $this->assertDatabaseMissing('payment_methods', [
            'bank_name' => 'Banco Original',
            'account_number' => 'ES0000000000000000000000',
        ]);
    }

    /**
     * No-admin no puede actualizar un método de pago.
     *
     * @return void
     */
    public function test_non_admin_cannot_update_a_payment_method()
    {
        $nonAdminUser = User::factory()->create(['role' => 'oper']);
        $this->actingAs($nonAdminUser);

        // Crear una instancia de PaymentMethod manualmente
        $paymentMethod = new PaymentMethod([
            'bank_name' => 'Banco de No Admin',
            'account_number' => 'ES2222222222222222222222',
        ]);
        $paymentMethod->save(); // Guardar en la base de datos

        $originalBankName = $paymentMethod->bank_name; // Guarda el nombre original para verificar

        $updatedData = [
            'bank_name' => 'Banco Intento Malicioso',
            'account_number' => 'ES1111111111111111111111',
        ];

        $response = $this->put('/payment-methods/' . $paymentMethod->id, $updatedData);

        $response->assertStatus(403); // Espera un error 403 (Forbidden)

        // Asegúrate de que el método de pago no ha sido actualizado en la base de datos
        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
            'bank_name' => $originalBankName, // El nombre del banco debe ser el original
        ]);
        $this->assertDatabaseMissing('payment_methods', [
            'id' => $paymentMethod->id,
            'bank_name' => 'Banco Intento Malicioso', // El nombre del banco no debe ser el malicioso
        ]);
    }

    /**
     * No se puede actualizar un método de pago con datos inválidos.
     *
     * @return void
     */
    public function test_cannot_update_payment_method_with_invalid_data()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        // Crear una instancia de PaymentMethod manualmente
        $paymentMethod = new PaymentMethod([
            'bank_name' => 'Banco Valido',
            'account_number' => 'ES1234567890123456789012',
        ]);
        $paymentMethod->save(); // Guardar en la base de datos

        $invalidData = [
            'bank_name' => '', // Intencionalmente vacío
            'account_number' => 'ES0987654321098765432109',
        ];

        $response = $this->put('/payment-methods/' . $paymentMethod->id, $invalidData, [
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422) // Espera un error 422 (Unprocessable Entity)
            ->assertJsonValidationErrors(['bank_name']); // Verifica que hay un error de validación para 'bank_name'

        // Asegúrate de que el método de pago no ha sido actualizado en la base de datos
        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
            'bank_name' => 'Banco Valido', // El nombre del banco debe seguir siendo el original
        ]);
        $this->assertDatabaseMissing('payment_methods', [
            'id' => $paymentMethod->id,
            'bank_name' => '', // No debe haberse actualizado a un nombre vacío
        ]);
    }

    /**
     * No se puede actualizar un método de pago no existente.
     *
     * @return void
     */
    public function test_cannot_update_non_existent_payment_method()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        $nonExistentId = 999;

        $updatedData = [
            'bank_name' => 'Banco Inexistente',
            'account_number' => 'ES1234567890123456789012',
        ];

        $response = $this->put('/payment-methods/' . $nonExistentId, $updatedData);

        $response->assertStatus(404);
    }
}
