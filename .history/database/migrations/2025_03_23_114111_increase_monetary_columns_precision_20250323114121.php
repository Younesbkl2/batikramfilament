<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update paiements table
        Schema::table('paiements', function (Blueprint $table) {
            $table->decimal('montantpaie', 15, 2)->change(); // Supports up to 999,999,999,999.99
        });

        // Update appartements table
        Schema::table('appartements', function (Blueprint $table) {
            $table->decimal('prixdelogt', 15, 2)->change();
        });

        // Update parkings table
        Schema::table('parkings', function (Blueprint $table) {
            $table->decimal('prixparking', 15, 2)->change();
        });

        // Update locals table
        Schema::table('locals', function (Blueprint $table) {
            $table->decimal('prixlocal', 15, 2)->change();
        });
    }

    public function down()
    {
        // Revert paiements table
        Schema::table('paiements', function (Blueprint $table) {
            $table->decimal('montantpaie', 10, 2)->change(); // Original precision
        });

        // Revert appartements table
        Schema::table('appartements', function (Blueprint $table) {
            $table->decimal('prixdelogt', 10, 2)->change();
        });

        // Revert parkings table
        Schema::table('parkings', function (Blueprint $table) {
            $table->decimal('prixparking', 10, 2)->change();
        });

        // Revert locals table
        Schema::table('locals', function (Blueprint $table) {
            $table->decimal('prixlocal', 10, 2)->change();
        });
    }
};