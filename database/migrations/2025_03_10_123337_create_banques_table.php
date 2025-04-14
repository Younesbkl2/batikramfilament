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
        // create_banques_table
        Schema::create('banques', function (Blueprint $table) {
            $table->id('codebanque');
            $table->string('nomdebanque')->nullable();
            $table->string('adressedebanque')->nullable();
            $table->integer('numdecompte')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banques');
    }
};
