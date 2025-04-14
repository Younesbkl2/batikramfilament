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
        // create_projets_table
        Schema::create('projets', function (Blueprint $table) {
            $table->id('codeprj');
            $table->string('Libelleprj')->nullable();
            $table->string('adresseprj')->nullable();
            $table->dateTime('datedebuttrvx')->nullable();
            $table->dateTime('datefintrvx')->nullable();
            $table->unsignedBigInteger('code_proprietaire')->nullable();
            $table->timestamps();
            
            $table->foreign('code_proprietaire')->references('code_proprietaire')->on('proprietaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
