<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('cif_nif')->unique(); // Tax identification number (nullable)
            $table->string('name'); // Company name / Business name
            $table->string('address'); // Address - Street name
            $table->string('number')->nullable(); // Address - Number (nullable)
            $table->string('letter')->nullable(); // Address - Letter (e.g., A, B) (nullable)
            $table->string('staircase')->nullable(); // Address - Staircase/Building (nullable)
            $table->string('phone')->nullable(); // Phone number (nullable)
            $table->string('town_municipality')->nullable(); // Town/Municipality
            $table->string('province')->nullable(); // Province
            $table->string('postal_code')->nullable(); // Postal code (nullable)
            $table->string('email')->nullable(); // Email address (nullable)
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
