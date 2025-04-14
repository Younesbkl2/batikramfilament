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
        // create_proprietaires_table
        Schema::create('proprietaires', function (Blueprint $table) {
            $table->id('code_proprietaire');
            $table->string('nom_proprietaire')->nullable();
            $table->string('prenom_proprietaire')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proprietaires');
    }
};
