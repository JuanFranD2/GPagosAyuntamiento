<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Liquidation;
use App\Models\PaymentMethod;
use App\Models\Proceeding;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'liquidation', 'user'])->orderBy('invoice_number', 'desc');
        $view = $request->query('view');
        $user = Auth::user();
        if ($user && ($user->isOper() || ($user->isAdmin() && $view === 'my'))) {
            $query->where('user_id', $user->id);
        }
        $invoices = $query->get();
        $clients = Client::all();
        $liquidations = Liquidation::all();
        return view('invoices.index', compact('invoices', 'clients', 'liquidations'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $clients = Client::all();
        $liquidations = Liquidation::all();
        $paymentMethods = PaymentMethod::all();
        $users = User::all();
        return view('invoices.create', compact('clients', 'liquidations', 'paymentMethods', 'users'));
    }

    /**
     * Store a newly created invoice in storage.
     *
     * @param  \App\Http\Requests\StoreInvoiceRequest  $request // Usar el Form Request aquí
     * @return \Illuminate\Http\RedirectResponse // O JsonResponse si manejas AJAX aquí, pero para create.blade.php es Redirección
     */
    public function store(StoreInvoiceRequest $request) // Usar StoreInvoiceRequest
    {
        // La validación se ejecuta automáticamente aquí.
        // Si falla, se redirige con errores y old input.
        // Si pasa, continuamos.

        try {
            DB::beginTransaction();

            // Acceder a los datos validados es opcional, puedes usar $request->input() también
            // $validatedData = $request->validated();

            // *** Lógica de creación/actualización de Cliente, Representante, Expediente y Liquidación ***
            // Descomenta y adapta tu lógica original aquí.
            // Usa $request->input(...) o $request->validated(...) para obtener los datos.

            // 1. Procesar y actualizar/crear el Cliente (Interesado)
            $clientData = $request->input('client');
            // Puedes añadir la conversión a mayúsculas aquí si la quitaste de prepareForValidation
            $clientData['cif_nif'] = strtoupper($clientData['cif_nif'] ?? ''); // Ejemplo de conversión
            $clientData['name'] = strtoupper($clientData['name'] ?? '');     // Ejemplo de conversión

            $client = Client::updateOrCreate(
                ['cif_nif' => $clientData['cif_nif']],
                $clientData
            );

            // 2. Procesar y actualizar/crear el Representante (Opcional)
            $representativeData = $request->input('representative');
            $representativeId = null;
            if (!empty($representativeData['cif_nif'])) {
                // Puedes añadir la conversión a mayúsculas aquí si la quitaste de prepareForValidation
                $representativeData['cif_nif'] = strtoupper($representativeData['cif_nif'] ?? '');
                $representativeData['name'] = strtoupper($representativeData['name'] ?? '');

                $representative = Client::updateOrCreate(
                    ['cif_nif' => $representativeData['cif_nif']],
                    $representativeData
                );
                $representativeId = $representative->id;
            }

            // 3. Procesar el Expediente (Proceedings)
            $liquidationDataForProceeding = $request->input('liquidation');
            // Puedes añadir la conversión a mayúsculas aquí si la quitaste de prepareForValidation
            $liquidationDataForProceeding['file_number'] = strtoupper($liquidationDataForProceeding['file_number'] ?? '');
            $liquidationDataForProceeding['concept'] = strtoupper($liquidationDataForProceeding['concept'] ?? '');

            $proceedingData = [
                'file_number' => $liquidationDataForProceeding['file_number'],
                'concept' => $liquidationDataForProceeding['concept'] ?? null,
            ];
            $proceeding = Proceeding::firstOrCreate(
                ['file_number' => $proceedingData['file_number']],
                $proceedingData
            );

            // 4. Procesar la Liquidación
            $liquidationDataForCreation = $request->input('liquidation');
            // Convertir comas a puntos para los campos numéricos ANTES de usarlos
            $liquidationDataForCreation['taxable_base'] = (float) str_replace(',', '.', $liquidationDataForCreation['taxable_base'] ?? 0);
            $liquidationDataForCreation['tax_rate'] = (float) str_replace(',', '.', $liquidationDataForCreation['tax_rate'] ?? 0);
            $liquidationDataForCreation['bond'] = (float) str_replace(',', '.', $liquidationDataForCreation['bond'] ?? 0);

            $liquidationDataForCreation['proceeding_id'] = $proceeding->id;

            // Asegúrate de que solo guardas los campos que existen en tu modelo Liquidation
            $liquidation = Liquidation::create([
                'proceeding_id' => $liquidationDataForCreation['proceeding_id'],
                'file_number' => $liquidationDataForCreation['file_number'], // <--- Añade esta línea para incluir el file_number
                'liquidation_date' => $liquidationDataForCreation['liquidation_date'] ?? Carbon::now(),
                'concept' => $liquidationDataForCreation['concept'],
                'taxable_base' => $liquidationDataForCreation['taxable_base'],
                'tax_rate' => $liquidationDataForCreation['tax_rate'],
                'bond' => $liquidationDataForCreation['bond'],
                // Si liquidation tiene total_to_pay, calcúlalo aquí o asegúrate que se hace en el modelo
                'total_fee' => $liquidationDataForCreation['taxable_base'] + ($liquidationDataForCreation['taxable_base'] * $liquidationDataForCreation['tax_rate'] / 100),
                'total_to_pay' => $liquidationDataForCreation['taxable_base'] + ($liquidationDataForCreation['taxable_base'] * $liquidationDataForCreation['tax_rate'] / 100) + $liquidationDataForCreation['bond'],
            ]);


            // --- Fin de la lógica de creación/actualización de modelos relacionados ---


            // 5. Generar automáticamente el número de Factura (Tu lógica existente)
            $currentMonth = Carbon::now()->format('m');
            $currentYear = Carbon::now()->format('Y');
            $prefixGenerate = "" . $currentMonth . "-" . $currentYear;

            $latestInvoice = Invoice::where('invoice_number', 'like', '%' . $prefixGenerate)
                ->orderByDesc('invoice_number')
                ->first();

            $nextSequence = 1;
            if ($latestInvoice) {
                $parts = explode('-', $latestInvoice->invoice_number);
                if (count($parts) >= 3) {
                    $lastSequence = (int)$parts[0];
                    $nextSequence = $lastSequence + 1;
                }
            }

            $formattedSequence = str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
            $generatedInvoiceNumber = $formattedSequence . '-' . $currentMonth . '-' . $currentYear;


            // 6. Crear la Factura
            $invoice = Invoice::create([
                'client_id' => $client->id, // Usar el ID del cliente creado/encontrado
                'representative_id' => $representativeId, // Usar el ID del representante (puede ser null)
                'liquidation_id' => $liquidation->id, // Usar el ID de la liquidación creada/encontrada
                'user_id' => Auth::id(),
                'invoice_number' => $generatedInvoiceNumber, // Usar el número generado
                'issue_date' => Carbon::now(), // Usar la fecha actual o si la envías desde el form: $request->input('issue_date') ?? Carbon::now()
                'total_amount' => $liquidation->total_to_pay, // Usar el total_to_pay de la liquidación
                'status' => 'Pendiente', // O el estado por defecto que necesites

            ]);

            DB::commit();

            // Redirigir con mensaje de éxito y la ID de la factura para posible descarga de PDF
            return redirect()->route('invoices.index')
                ->with('success', 'Factura creada correctamente.')
                ->with('download_invoice_id', $invoice->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating invoice: ' . $e->getMessage(), ['exception' => $e]);

            // Redirigir con mensaje de error y los datos antiguos
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear la factura: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'representative', 'liquidation', 'user']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        // NOTA: Este método `edit` ya no es estrictamente necesario si solo usas el modal
        // de edición en el index.blade.php. Si mantienes una ruta separada para /invoices/{id}/edit,
        // este método sigue siendo útil. Asegúrate de que tu ruteo sea coherente.
        $invoice->load(['client', 'liquidation', 'user', 'paymentMethod']);
        $clients = Client::all();
        $liquidations = Liquidation::all();
        $paymentMethods = PaymentMethod::all();
        $users = User::all(); // O puedes filtrar usuarios si es necesario

        return view('invoices.edit', compact('invoice', 'clients', 'liquidations', 'paymentMethods', 'users'));
    }

    /**
     * Update the specified invoice in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoiceRequest  $request // Usamos el Form Request aquí
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice) // Modificado para usar UpdateInvoiceRequest
    {
        // La validación se ejecuta automáticamente por UpdateInvoiceRequest
        // Si falla, se redirige con errores o retorna JSON si es una petición AJAX (422 Unprocessable Entity)

        try {
            // No es estrictamente necesario DB::beginTransaction() para una sola actualización,
            // pero lo mantenemos si la lógica se vuelve más compleja en el futuro.
            // Si no hay otras operaciones de base de datos que necesiten atomizar,
            // puedes quitar el beginTransaction/commit/rollback.

            $invoice->update([
                'client_id' => $request->client_id,
                'liquidation_id' => $request->liquidation_id,
                'invoice_number' => $request->invoice_number,
                'issue_date' => $request->issue_date,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                // Actualizar otros campos si los agregaste al modal de edición
            ]);

            // Si no hay beginTransaction, no hay commit. La actualización es atómica por sí misma.
            // DB::commit();

            // Para peticiones AJAX, retornar JSON con la factura actualizada
            if ($request->expectsJson()) {
                // Recargar relaciones necesarias para la respuesta AJAX
                $invoice->load(['client', 'liquidation']);
                return response()->json(['invoice' => $invoice], 200);
            }

            // Para peticiones no-AJAX, redirigir
            return redirect()->route('invoices.index')->with('success', 'Factura actualizada correctamente.');
        } catch (\Exception $e) {
            // Si no hay beginTransaction, no hay rollback.
            // DB::rollBack();
            Log::error('Error updating invoice: ' . $e->getMessage(), ['exception' => $e]);

            // Para peticiones AJAX, retornar JSON con error
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Ocurrió un error al actualizar la factura: ' . $e->getMessage()], 500);
            }

            // Para peticiones no-AJAX, redirigir con error
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar la factura: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified invoice from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Invoice $invoice)
    {
        // Puedes añadir lógica de autorización aquí (ej. Gate::authorize('delete', $invoice);)

        try {
            // Puedes añadir comprobaciones de restricciones aquí antes de eliminar
            // Por ejemplo, si un pago no puede eliminarse si está asociado a algún otro modelo
            // $relatedModel = $invoice->someRelationship()->exists();
            // if ($relatedModel) {
            //     throw new \Exception('Cannot delete invoice because it has related data.');
            // }

            $invoice->delete();

            // Para peticiones AJAX, retornar éxito
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Pago eliminado correctamente.'], 200);
            }

            // Para peticiones no-AJAX, redirigir
            return redirect()->route('invoices.index')->with('success', 'Factura eliminada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error deleting invoice: ' . $e->getMessage(), ['exception' => $e]);

            // Para peticiones AJAX, retornar JSON con error y un código de estado apropiado
            if (request()->expectsJson()) {
                // 409 Conflict es común para errores de integridad referencial
                return response()->json(['message' => $e->getMessage()], 409);
            }

            // Para peticiones no-AJAX, redirigir con error
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the invoices for the pays view.
     * NOTA: Esta función parece ser para otra vista ('seePays') y usa paginación,
     * diferente del index reestructurado con DataTables. La mantenemos aparte.
     */
    public function seePays()
    {
        $invoices = Invoice::with(['client', 'liquidation'])->paginate(10);
        return view('seePays', compact('invoices'));
    }

    /**
     * Find a client by CIF/NIF.
     *
     * @param  string $cif_nif
     * @return \Illuminate\Http\JsonResponse
     */
    public function findClientByCifNif(string $cif_nif)
    {
        // Validar que el CIF/NIF recibido tiene un formato básico
        $validator = \Illuminate\Support\Facades\Validator::make(['cif_nif' => $cif_nif], [
            'cif_nif' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Retorna un error si el formato no es válido
            return response()->json(['error' => 'Invalid CIF/NIF format'], 400);
        }

        // Buscar el cliente. first() retorna el primer resultado o null.
        $client = Client::where('cif_nif', $cif_nif)->first();

        if ($client) {
            // Retorna los datos del cliente como JSON
            return response()->json($client);
        } else {
            // Retorna una respuesta 404 si no se encuentra el cliente
            return response()->json(['message' => 'Client not found'], 404);
        }
    }
    /**
     * Find the concept for a proceeding by file number.
     *
     * @param  string $file_number
     * @return \Illuminate\Http\JsonResponse
     */
    public function findConceptByFileNumber(string $file_number)
    {
        // Validar el número de expediente recibido
        $validator = \Illuminate\Support\Facades\Validator::make(['file_number' => $file_number], [
            'file_number' => 'required|string|max:255', // Debe coincidir con tu validación en store
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid file number format'], 400);
        }

        // Buscar el expediente por file_number y seleccionar solo el campo 'concept'
        $proceeding = Proceeding::where('file_number', $file_number)
            ->select('concept') // Seleccionamos solo el concepto
            ->first();

        if ($proceeding) {
            // Retorna el concepto (y quizás el file_number para confirmación) como JSON
            return response()->json(['concept' => $proceeding->concept, 'file_number' => $file_number]);
        } else {
            // Retorna una respuesta 404 si no se encuentra el expediente
            return response()->json(['message' => 'Proceeding not found'], 404);
        }
    }

    public function downloadPdf(Invoice $invoice)
    {
        // Cargar las relaciones necesarias para la vista del PDF
        $invoice->load(['client', 'representative', 'liquidation', 'user']);

        // Cargar todos los métodos de pago disponibles
        $paymentMethods = PaymentMethod::all();

        // Cargar la vista Blade específica para el PDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'paymentMethods'));

        // Definir el nombre del archivo PDF
        $filename = 'invoice_' . ($invoice->invoice_number ?? $invoice->id) . '.pdf';

        // Retornar el PDF para mostrar en el navegador (stream)
        return $pdf->stream($filename);
    }
}
