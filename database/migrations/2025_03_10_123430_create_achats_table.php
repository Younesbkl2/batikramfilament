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
        Schema::create('achats', function (Blueprint $table) {
            $table->id('codachat');
            $table->string('codeclient')->nullable();
            $table->unsignedBigInteger('codeprod')->nullable();
            $table->unsignedBigInteger('codebanque')->nullable();
            $table->string('numappartement')->nullable();
            $table->string('numparking')->nullable();
            $table->string('Numlocal')->nullable();
            $table->text('Observations')->nullable();
            $table->timestamps();
            // Remove the ID_ATTESTATION column and its foreign key
        
            $table->foreign('codeclient')->references('codeclient')->on('clients');
            $table->foreign('codeprod')->references('codeprod')->on('produits');
            $table->foreign('codebanque')->references('codebanque')->on('banques');
            $table->foreign('numappartement')->references('numappartement')->on('appartements');
            $table->foreign('numparking')->references('numparking')->on('parkings');
            $table->foreign('Numlocal')->references('Numlocal')->on('locals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
