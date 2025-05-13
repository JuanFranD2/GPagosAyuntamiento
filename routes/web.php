<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/', function () {
    return redirect()->route('dashboard'); // o cualquier otra vista que quieras mostrar
});

// Vista dashboard protegida con auth y verified
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Perfil - Restringido a admin
Route::view('profile', 'profile')
    ->middleware(['auth', 'role:admin']) // Agregamos el middleware 'role:admin'
    ->name('profile');

// Rutas protegidas con autenticación (accesibles para 'admin' y 'oper')
Route::middleware(['auth'])->group(function () {

    // Rutas de invoices (accesibles para usuarios autenticados, incluyendo 'oper')
    Route::resource('invoices', InvoiceController::class)->except(['show']);
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/show/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'downloadPdf'])->name('invoices.download');

    // Rutas AJAX de soporte para invoices (necesarias para el formulario de creación/edición, accesibles a 'oper')
    Route::get('/clients/find-by-cif-nif/{cif_nif}', [InvoiceController::class, 'findClientByCifNif'])->name('clients.find-by-cif-nif');
    Route::get('/proceedings/find-concept-by-file-number/{file_number}', [InvoiceController::class, 'findConceptByFileNumber'])->name('proceedings.find-concept-by-file-number');

    // Rutas de payment-methods: Restringir acceso a 'oper' (solo 'admin' puede acceder)
    // Aplicar el middleware 'role:admin'
    // Esto aplicará la restricción tanto al index explícito como a todas las rutas del resource.
    Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->middleware('role:admin')->name('payment-methods.index');
    Route::resource('payment-methods', PaymentMethodController::class)->middleware('role:admin');

    // Rutas de users: Restringir acceso a 'oper' (solo 'admin' puede acceder)
    // Aplicar el middleware 'role:admin' a todo el resource
    Route::resource('users', UserController::class)->middleware('role:admin');
});

// Auth scaffolding (login, register, etc.)
require __DIR__ . '/auth.php';
