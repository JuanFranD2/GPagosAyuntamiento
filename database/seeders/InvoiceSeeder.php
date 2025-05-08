<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Liquidation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; // Import DB facade

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all()->pluck('id');
        $liquidations = Liquidation::all()->pluck('id');
        $users = User::all()->pluck('id');

        // Assuming all clients can potentially be representatives
        $representatives = $clients;

        if ($clients->isNotEmpty() && $liquidations->isNotEmpty() && $users->isNotEmpty()) {
            $invoicesData = [];
            $currentDate = Carbon::now(); // Get the current date and time
            $currentMonth = $currentDate->format('m'); // Get the current month in 2 digits
            $currentYear = $currentDate->format('Y'); // Get the current year in 4 digits

            for ($i = 0; $i < 20; $i++) { // Generate 20 sample invoices
                // Generate the 4-digit sequence number with leading zeros
                $sequence = str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                // Construct the invoice number in the desired format
                $invoiceNumber = $sequence . '-' . $currentMonth . '-' . $currentYear;

                $invoicesData[] = [
                    'client_id' => $clients->random(),
                    'representative_id' => $representatives->isNotEmpty() ? $representatives->random() : null,
                    'liquidation_id' => $liquidations->random(),
                    'user_id' => $users->random(),
                    'invoice_number' => $invoiceNumber, // Use the new format
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                ];
            }
            DB::table('invoices')->insert($invoicesData);
        } else {
            $this->command->warn('Warning: Ensure clients, liquidations, and users are seeded before running InvoiceSeeder.');
        }
    }
}
