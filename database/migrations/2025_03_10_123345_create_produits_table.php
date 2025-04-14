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
        // create_produits_table
        Schema::create('produits', function (Blueprint $table) {
            $table->id('codeprod');
            $table->unsignedBigInteger('code_proprietaire')->nullable();
            $table->string('Typeproduit')->nullable();
            $table->timestamps();
            
            $table->foreign('code_proprietaire')->references('code_proprietaire')->on('proprietaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
