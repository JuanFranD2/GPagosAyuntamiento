<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\PaymentMethod;
use App\Models\User;

class DeletePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    // ... (Tus tests existentes para crear y actualizar métodos de pago) ...

    /**
     * Admin puede eliminar un método de pago.
     *
     * @return void
     */
    public function test_admin_can_delete_a_payment_method()
    {
        $this->withoutExceptionHandling(); // Descomenta para ver errores detallados si el test falla.
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        // Crear un método de pago para eliminarlo
        $paymentMethod = new PaymentMethod([
            'bank_name' => 'Banco a Eliminar',
            'account_number' => 'ES1111222233334444555566',
        ]);
        $paymentMethod->save();

        // Verificar que el método de pago existe antes de la eliminación
        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
        ]);

        // Realizar la petición DELETE
        $response = $this->delete('/payment-methods/' . $paymentMethod->id);

        $response->assertStatus(200) // Espera un 200 OK para éxito de la petición AJAX
            ->assertJson(['success' => 'Método de pago eliminado correctamente.']); // Verifica el mensaje de éxito

        // Verificar que el método de pago ya no existe en la base de datos
        $this->assertDatabaseMissing('payment_methods', [
            'id' => $paymentMethod->id,
        ]);
    }

    /**
     * No-admin no puede eliminar un método de pago.
     *
     * @return void
     */
    public function test_non_admin_cannot_delete_a_payment_method()
    {
        $nonAdminUser = User::factory()->create(['role' => 'oper']);
        $this->actingAs($nonAdminUser);

        // Crear un método de pago que el no-admin intentará eliminar
        $paymentMethod = new PaymentMethod([
            'bank_name' => 'Banco Protegido',
            'account_number' => 'ES7777888899990000111122',
        ]);
        $paymentMethod->save();

        // Realizar la petición DELETE como no-admin
        $response = $this->delete('/payment-methods/' . $paymentMethod->id);

        $response->assertStatus(403); // Espera un error 403 (Forbidden)

        // Verificar que el método de pago aún existe en la base de datos
        $this->assertDatabaseHas('payment_methods', [
            'id' => $paymentMethod->id,
        ]);
    }

    /**
     * No se puede eliminar un método de pago no existente.
     *
     * @return void
     */
    public function test_cannot_delete_non_existent_payment_method()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);

        $nonExistentId = 9999; // Un ID que sabes que no existe

        // Realizar la petición DELETE para un ID inexistente
        $response = $this->delete('/payment-methods/' . $nonExistentId);

        $response->assertStatus(404); // Espera un error 404 (Not Found)
    }
}
