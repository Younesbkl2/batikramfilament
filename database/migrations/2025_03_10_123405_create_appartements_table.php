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
        // create_appartements_table
        Schema::create('appartements', function (Blueprint $table) {
            $table->string('numappartement')->primary();
            $table->string('blocappartement')->nullable();
            $table->decimal('superficie', 10, 2)->nullable();
            $table->string('etage')->nullable();
            $table->string('coteappartement')->nullable();
            $table->unsignedBigInteger('codeprod')->nullable();
            $table->boolean('reservation')->nullable();
            $table->decimal('prixdelogt', 10, 2)->nullable();
            $table->unsignedBigInteger('codeprj')->nullable();
            $table->unsignedBigInteger('code_proprietaire')->nullable();
            $table->string('NumEDD')->nullable();
            $table->integer('Numpiece')->nullable();
            $table->text('obs')->nullable();
            $table->timestamps();
            
            $table->foreign('codeprod')->references('codeprod')->on('produits');
            $table->foreign('codeprj')->references('codeprj')->on('projets');
            $table->foreign('code_proprietaire')->references('code_proprietaire')->on('proprietaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
