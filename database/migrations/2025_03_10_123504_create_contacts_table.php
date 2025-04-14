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
        // create_contacts_table
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('Champ1')->nullable();
            $table->string('Champ2')->nullable();
            $table->string('Champ3')->nullable();
            $table->string('Champ4')->nullable();
            $table->string('Champ5')->nullable();
            $table->string('Champ6')->nullable();
            $table->string('Champ7')->nullable();
            $table->text('OBS')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
