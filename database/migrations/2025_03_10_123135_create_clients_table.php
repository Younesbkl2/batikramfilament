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
        // create_clients_table
        Schema::create('clients', function (Blueprint $table) {
            $table->string('codeclient')->primary();
            $table->string('nomclient')->nullable();
            $table->string('prenomclient')->nullable();
            $table->string('adresseclient')->nullable();
            $table->string('Numdetel')->nullable();
            $table->string('NUM_TEL')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('dossier')->nullable();
            $table->dateTime('date_de_naissance')->nullable();
            $table->boolean('Vsp_publié')->nullable();
            $table->timestamps();
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
