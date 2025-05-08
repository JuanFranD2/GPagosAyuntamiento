<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import the Validator facade

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
        // This method is not needed for the modal approach,
        // but kept for completeness if you ever need a dedicated create page.
        // return view('payment_methods.create');
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255', // Made required based on typical form
            'account_number' => 'required|string|max:255', // Made required
            // Add any other validation rules as needed
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON with a 422 status code
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validation passed, create the new payment method
        $paymentMethod = PaymentMethod::create($validator->validated());

        // Return the newly created payment method as JSON with a 201 status code
        return response()->json($paymentMethod, 201); // 201 Created
    }


    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        // Method to display the details of a payment method
        // You will need a 'payment_methods.show' view for this
        return view('payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        // This method is not needed for an AJAX edit modal,
        // but kept for completeness if you ever need a dedicated edit page.
        // return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        // Define validation rules for update
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255', // Assuming required for update too
            'account_number' => 'required|string|max:255', // Assuming required for update too
            // Add any other validation rules as needed for update
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return validation errors as JSON with a 422 status code
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validation passed, update the payment method
        $paymentMethod->update($validator->validated());

        // Return the updated payment method as JSON
        // Returning the updated model allows the frontend to easily update the DataTables row
        return response()->json($paymentMethod); // 200 OK is default
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Method to delete a payment method
        try {
            $paymentMethod->delete();
            // Return an empty or success JSON response for the AJAX request
            return response()->json(['success' => 'Método de pago eliminado correctamente.']);
        } catch (\Exception $e) {
            // Optional: Log the error
            // \Log::error('Error deleting payment method: ' . $e->getMessage());
            // Return a JSON error for the AJAX request
            return response()->json(['error' => 'Ocurrió un error al eliminar el método de pago.'], 500);
        }
    }
}
