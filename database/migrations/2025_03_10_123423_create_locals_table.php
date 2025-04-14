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
        // create_locals_table
        Schema::create('locals', function (Blueprint $table) {
            $table->string('Numlocal')->primary();
            $table->decimal('surfacelocal', 10, 2)->nullable();
            $table->unsignedBigInteger('codeprod')->nullable();
            $table->decimal('prixlocal', 10, 2)->nullable();
            $table->boolean('reservationlocal')->nullable();
            $table->timestamps();
            
            $table->foreign('codeprod')->references('codeprod')->on('produits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locals');
    }
};
