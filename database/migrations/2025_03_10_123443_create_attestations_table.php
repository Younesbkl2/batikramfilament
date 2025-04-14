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
        Schema::create('attestations', function (Blueprint $table) {
            $table->id('ID_ATTESTATION');
            $table->boolean('reservation')->nullable();
            $table->boolean('reservation_notaire')->nullable();
            $table->boolean('prestation')->nullable();
            $table->boolean('remise_des_clés')->nullable();
            $table->string('Num_attestation')->nullable();
            $table->unsignedBigInteger('codachat')->nullable(); // Foreign key to achats
            $table->dateTime('date_attestation')->nullable();
            $table->text('OBS')->nullable();
            $table->timestamps();
        
            $table->foreign('codachat')->references('codachat')->on('achats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
};
