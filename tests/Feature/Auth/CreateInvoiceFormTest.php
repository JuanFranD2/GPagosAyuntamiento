<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Liquidation;
use App\Models\Proceeding;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Auth\Access\AuthorizationException; // ¡Añadido! Necesario para el test de autorización
use Illuminate\Database\QueryException; // ¡Añadido! Necesario para el test de datos relacionados

class InvoiceDeletionTest extends TestCase
{
    use RefreshDatabase; // Esto resetea la base de datos para cada test

    #[Test]
    public function an_invoice_can_be_deleted(): void
    {
        // ¡Importante para depurar! Si este test falla, verás la excepción real.
        $this->withoutExceptionHandling();

        // 1. Preparación: Crear los datos necesarios de forma explícita
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            // Asegúrate de incluir cualquier otro campo obligatorio de tu modelo User
            'role' => 'admin', // Asegúrate de que este usuario tenga los permisos necesarios para eliminar, si los tienes.
        ]);
        $this->actingAs($user); // Autentica al usuario

        $client = Client::create([
            'cif_nif' => '12345678X',
            'name' => 'Cliente de Prueba',
            'address' => 'Calle Falsa 123',
            'postal_code' => '28001',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600111222',
            'email' => 'cliente.prueba@example.com',
        ]);

        $representative = Client::create([
            'cif_nif' => '87654321Y',
            'name' => 'Representante de Prueba',
            'address' => 'Calle del Sol 45',
            'postal_code' => '28002',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600333444',
            'email' => 'representante.prueba@example.com',
        ]);

        $proceeding = Proceeding::create([
            'file_number' => 'EXP-DEL-001',
            'concept' => 'Concepto de Prueba Eliminación',
        ]);

        $taxableBase = 1000.00;
        $taxRate = 21.00;
        $bond = 150.00;
        $totalFee = $taxableBase + ($taxableBase * $taxRate / 100);
        $totalToPay = $totalFee + $bond;

        $liquidation = Liquidation::create([
            'proceeding_id' => $proceeding->id,
            'file_number' => $proceeding->file_number,
            'liquidation_date' => Carbon::now()->format('Y-m-d'),
            'concept' => 'Liquidacion para Eliminación',
            'taxable_base' => $taxableBase,
            'tax_rate' => $taxRate,
            'bond' => $bond,
            'total_fee' => $totalFee,
            'total_to_pay' => $totalToPay,
        ]);

        $invoice = Invoice::create([
            'client_id' => $client->id,
            'representative_id' => $representative->id, // Asegúrate de que 'representative_id' es un campo fillable en tu modelo Invoice
            'liquidation_id' => $liquidation->id,
            'user_id' => $user->id,
            'invoice_number' => 'DEL-' . Carbon::now()->format('mdYHis'),
            'issue_date' => Carbon::now(),
            'total_amount' => $liquidation->total_to_pay,
            'status' => 'Pendiente',
        ]);

        // *** PASO CLAVE PARA DEPURAR EL ID ***
        // Descomenta la siguiente línea y ejecuta el test.
        // Esto detendrá el test aquí y mostrará el contenido de $invoice.
        // Si el 'id' es 'null', el problema está en tu modelo o migración.
        // dd($invoice->toArray()); 
        // dump('Invoice ID after creation:', $invoice->id); // Puedes usar dump en lugar de dd si quieres que el test continúe

        // Verificamos que la factura existe antes de la eliminación
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
        $this->assertCount(1, Invoice::all());

        // 2. Acción: Realizar la solicitud DELETE al endpoint de eliminación
        $response = $this->delete(route('invoices.destroy', $invoice));

        // 3. Verificación: Asegurarse de que la eliminación fue exitosa
        $response->assertRedirect(route('invoices.index'))
            ->assertSessionHas('success', 'Factura eliminada correctamente.');

        // Verificar que la factura ya no existe en la base de datos
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
        $this->assertCount(0, Invoice::all());
    }

    #[Test]
    public function an_invoice_cannot_be_deleted_if_it_has_related_data(): void
    {
        // ¡Importante! Activa withoutExceptionHandling() para que las excepciones de DB se lancen directamente.
        $this->withoutExceptionHandling();

        // 1. Preparación: Crear los datos
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // El usuario debe tener permisos de eliminación
        ]);
        $this->actingAs($user);

        $client = Client::create([
            'cif_nif' => '98765432W',
            'name' => 'Cliente Restringido',
            'address' => 'Avenida Principal 1',
            'postal_code' => '28003',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600555666',
            'email' => 'cliente.restringido@example.com',
        ]);

        $representative = Client::create([ // Necesario para crear la factura
            'cif_nif' => '54321098Z',
            'name' => 'Representante Restringido',
            'address' => 'Calle Secundaria 2',
            'postal_code' => '28005',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600999000',
            'email' => 'rep.restringido@example.com',
        ]);

        $proceeding = Proceeding::create([
            'file_number' => 'EXP-RESTRICT-001',
            'concept' => 'Concepto Restringido',
        ]);

        $taxableBase = 2000.00;
        $taxRate = 10.00;
        $bond = 50.00;
        $totalFee = $taxableBase + ($taxableBase * $taxRate / 100);
        $totalToPay = $totalFee + $bond;

        $liquidation = Liquidation::create([
            'proceeding_id' => $proceeding->id,
            'file_number' => $proceeding->file_number,
            'liquidation_date' => Carbon::now()->format('Y-m-d'),
            'concept' => 'Liquidacion Restringida',
            'taxable_base' => $taxableBase,
            'tax_rate' => $taxRate,
            'bond' => $bond,
            'total_fee' => $totalFee,
            'total_to_pay' => $totalToPay,
        ]);

        $invoice = Invoice::create([
            'client_id' => $client->id,
            'representative_id' => $representative->id, // Añadido
            'liquidation_id' => $liquidation->id,
            'user_id' => $user->id,
            'invoice_number' => 'RESTRICT-' . Carbon::now()->format('mdYHis'),
            'issue_date' => Carbon::now(),
            'total_amount' => $liquidation->total_to_pay,
            'status' => 'Pendiente',
        ]);

        // --- ¡PASO CLAVE para este test! Simulación de una restricción ---
        // Necesitas un modelo y una migración que tenga una clave foránea
        // a `invoices` con una restricción `restrictOnDelete()`.
        // Por ejemplo, si tienes un modelo `Payment` para pagos de facturas:
        // class CreatePaymentsTable extends Migration {
        //     public function up() {
        //         Schema::create('payments', function (Blueprint $table) {
        //             $table->id();
        //             $table->foreignId('invoice_id')->constrained()->restrictOnDelete(); // <-- ¡Esta es la clave!
        //             // ... otros campos de pago
        //         });
        //     }
        // }
        // Si no tienes un modelo así, tendrías que simular la restricción con otro modelo.

        // ¡Descomenta la siguiente línea si tienes un modelo que depende de Invoice!
        // Asegúrate de importar `App\Models\Payment` si lo usas.
        // \App\Models\Payment::create(['invoice_id' => $invoice->id, 'amount' => 50.00, 'payment_date' => now()]);

        // 2. Acción: Intentar eliminar la factura
        // Si la restricción de base de datos se activa y withoutExceptionHandling() está activo,
        // esperamos que Laravel lance una QueryException.
        $this->expectException(QueryException::class);

        $response = $this->delete(route('invoices.destroy', $invoice));

        // Las aserciones de estado y sesión no se ejecutarán si se lanza la excepción,
        // que es el comportamiento esperado para que este test pase.
        // Si el test llega a este punto, significa que la excepción NO se lanzó.
        // En ese caso, la aserción de sesión fallaría, o la factura se eliminaría.
        // $response->assertStatus(302);
        // $response->assertSessionHas('error');
        // $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    #[Test]
    public function an_unauthorized_user_cannot_delete_an_invoice(): void
    {
        // ¡Importante! Activa withoutExceptionHandling() para que las excepciones de autorización se lancen directamente.
        $this->withoutExceptionHandling();

        // 1. Preparación: Crear los datos para el escenario
        $authorizedUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Asegúrate de que 'admin' es el rol autorizado para eliminar
        ]);

        $unauthorizedUser = User::create([
            'name' => 'Unauthorized User',
            'email' => 'unauth@example.com',
            'password' => Hash::make('password'),
            'role' => 'oper', // Asegúrate de que 'oper' es el rol no autorizado (o similar)
        ]);

        $client = Client::create([
            'cif_nif' => '11111111Z',
            'name' => 'Cliente Autorizado',
            'address' => 'Plaza Mayor 1',
            'postal_code' => '28004',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600777888',
            'email' => 'cliente.autorizado@example.com',
        ]);

        $representative = Client::create([ // Necesario para crear la factura
            'cif_nif' => '99999999Q',
            'name' => 'Representante Autorizado',
            'address' => 'Calle de la Luna 7',
            'postal_code' => '28006',
            'town' => 'Madrid',
            'province' => 'Madrid',
            'phone' => '600111000',
            'email' => 'rep.autorizado@example.com',
        ]);

        $proceeding = Proceeding::create([
            'file_number' => 'EXP-AUTH-001',
            'concept' => 'Concepto Autorizado',
        ]);

        $taxableBase = 3000.00;
        $taxRate = 4.00;
        $bond = 20.00;
        $totalFee = $taxableBase + ($taxableBase * $taxRate / 100);
        $totalToPay = $totalFee + $bond;

        $liquidation = Liquidation::create([
            'proceeding_id' => $proceeding->id,
            'file_number' => $proceeding->file_number,
            'liquidation_date' => Carbon::now()->format('Y-m-d'),
            'concept' => 'Liquidacion Autorizada',
            'taxable_base' => $taxableBase,
            'tax_rate' => $taxRate,
            'bond' => $bond,
            'total_fee' => $totalFee,
            'total_to_pay' => $totalToPay,
        ]);

        $invoice = Invoice::create([
            'client_id' => $client->id,
            'representative_id' => $representative->id, // Añadido
            'liquidation_id' => $liquidation->id,
            'user_id' => $authorizedUser->id, // Factura creada por el usuario autorizado
            'invoice_number' => 'UNAUTH-' . Carbon::now()->format('mdYHis'),
            'issue_date' => Carbon::now(),
            'total_amount' => $liquidation->total_to_pay,
            'status' => 'Pendiente',
        ]);

        // 2. Acción: Intentar eliminar como usuario no autorizado
        $this->actingAs($unauthorizedUser); // Autentica al usuario no autorizado

        // Esperamos una AuthorizationException si tienes una Policy o Gate correctamente configurado
        $this->expectException(AuthorizationException::class);

        $response = $this->delete(route('invoices.destroy', $invoice));

        // Las aserciones de estado no se ejecutarán si se lanza la excepción.
        // Si el test llega a este punto, significa que la excepción NO se lanzó.
        // $response->assertStatus(403);
        // $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }
}
