<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidations', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('file_number'); // File/Case number
            $table->date('liquidation_date'); // Liquidation date
            $table->text('concept')->nullable(); // Concept/Description (nullable)
            $table->decimal('taxable_base', 10, 2)->nullable(); // Taxable base (nullable), 10 total digits, 2 decimal places
            $table->decimal('tax_rate', 5, 2)->nullable(); // Tax rate (nullable), 5 total digits, 2 decimal places
            $table->decimal('total_fee', 10, 2)->nullable(); // Total fee/tax (nullable), 10 total digits, 2 decimal places
            $table->decimal('bond', 10, 2)->nullable(); // Deposit/Bond (nullable), 10 total digits, 2 decimal places
            $table->decimal('total_to_pay', 10, 2)->nullable(); // Total to pay (nullable), 10 total digits, 2 decimal places
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liquidations');
    }
}
