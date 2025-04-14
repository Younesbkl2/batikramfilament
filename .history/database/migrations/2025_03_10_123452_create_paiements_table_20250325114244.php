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
        // create_paiements_table
        Schema::create('paiements', function (Blueprint $table) {
            $table->id('codepaie');
            $table->string('modepaie')->nullable();
            $table->unsignedBigInteger('codebanque')->nullable();
            $table->decimal('montantpaie', 10, 2)->nullable();
            $table->dateTime('datepaie')->nullable();
            $table->string('codeclient')->nullable();
            $table->unsignedBigInteger('codachat')->nullable();
            $table->timestamps();
            
            $table->foreign('codebanque')->references('codebanque')->on('banques');
            $table->foreign('codeclient')->references('codeclient')->on('clients');
            $table->foreign('codachat')->references('codachat')->on('achats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
