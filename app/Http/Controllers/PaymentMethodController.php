<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\StorePaymentMethodRequest; // Importar el Store Request
use App\Http\Requests\UpdatePaymentMethodRequest; // Importar el Update Request
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator; // Ya no es necesario importar el Validator facade directamente

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the payment methods.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        // Este método no es necesario para el enfoque modal,
        // pero se mantiene para completar si alguna vez necesitas una página de creación dedicada.
        // return view('payment_methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(StorePaymentMethodRequest $request) // Inyectar StorePaymentMethodRequest
    {
        // La validación se realiza automáticamente por StorePaymentMethodRequest.
        // Si la validación falla, Laravel automáticamente redirige de vuelta con los errores
        // o devuelve una respuesta JSON 422 para peticiones AJAX.

        // Crea el nuevo método de pago usando los datos validados
        $paymentMethod = PaymentMethod::create($request->validated());

        // Retorna el método de pago recién creado como JSON con un código de estado 201
        return response()->json($paymentMethod, 201); // 201 Created
    }


    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        // Método para mostrar los detalles de un método de pago
        // Necesitarás una vista 'payment_methods.show' para esto
        return view('payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        // Este método no es necesario para un modal de edición AJAX,
        // pero se mantiene para completar si alguna vez necesitas una página de edición dedicada.
        // return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod) // Inyectar UpdatePaymentMethodRequest
    {
        // La validación se realiza automáticamente por UpdatePaymentMethodRequest.
        // Si la validación falla, Laravel automáticamente redirige de vuelta con los errores
        // o devuelve una respuesta JSON 422 para peticiones AJAX.

        // Actualiza el método de pago usando los datos validados
        $paymentMethod->update($request->validated());

        // Retorna el método de pago actualizado como JSON
        // Retornar el modelo actualizado permite que el frontend actualice fácilmente la fila de DataTables
        return response()->json($paymentMethod); // 200 OK es el predeterminado
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Método para eliminar un método de pago
        try {
            $paymentMethod->delete();
            // Retorna una respuesta JSON vacía o de éxito para la solicitud AJAX
            return response()->json(['success' => 'Método de pago eliminado correctamente.']);
        } catch (\Exception $e) {
            // Opcional: Registrar el error
            // \Log::error('Error deleting payment method: ' . $e->getMessage());
            // Retorna un error JSON para la solicitud AJAX
            return response()->json(['error' => 'Ocurrió un error al eliminar el método de pago.'], 500);
        }
    }
}
