<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id'); // Custom primary key name for invoice ID
            $table->unsignedBigInteger('client_id'); // Foreign key referencing the 'clients' table (interesado)
            $table->unsignedBigInteger('representative_id')->nullable(); // Foreign key referencing the 'clients' table (representante)
            $table->unsignedBigInteger('liquidation_id'); // Foreign key referencing the 'liquidations' table
            $table->unsignedBigInteger('user_id');
            $table->string('invoice_number')->nullable(); // Invoice number (nullable)
            $table->string('pdf_url')->nullable(); // Campo para guardar la ruta o URL del PDF
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients'); // Interesado
            $table->foreign('representative_id')->references('id')->on('clients'); // Representante
            $table->foreign('liquidation_id')->references('id')->on('liquidations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
