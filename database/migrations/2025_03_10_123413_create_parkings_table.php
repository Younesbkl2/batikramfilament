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
        // create_parkings_table
        Schema::create('parkings', function (Blueprint $table) {
            $table->string('numparking')->primary();
            $table->decimal('surfaceparking', 10, 2)->nullable();
            $table->unsignedBigInteger('codeprod')->nullable();
            $table->decimal('prixparking', 10, 2)->nullable();
            $table->boolean('reservationparking')->nullable();
            $table->timestamps();
            
            $table->foreign('codeprod')->references('codeprod')->on('produits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};
