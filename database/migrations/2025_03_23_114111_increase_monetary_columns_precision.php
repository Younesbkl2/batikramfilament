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
            $table->decimal('montantpaie', 18, 2)->nullable()->change(); // Increased to 18 digits
        });

        // Update appartements table
        Schema::table('appartements', function (Blueprint $table) {
            $table->decimal('prixdelogt', 18, 2)->nullable()->change();
        });

        // Update parkings table
        Schema::table('parkings', function (Blueprint $table) {
            $table->decimal('prixparking', 18, 2)->nullable()->change();
        });

        // Update locals table
        Schema::table('locals', function (Blueprint $table) {
            $table->decimal('prixlocal', 18, 2)->nullable()->change();
        });
    }

    public function down()
    {
        // Revert with original nullable state and precision
        Schema::table('paiements', function (Blueprint $table) {
            $table->decimal('montantpaie', 10, 2)->nullable()->change();
        });

        Schema::table('appartements', function (Blueprint $table) {
            $table->decimal('prixdelogt', 10, 2)->nullable()->change();
        });

        Schema::table('parkings', function (Blueprint $table) {
            $table->decimal('prixparking', 10, 2)->nullable()->change();
        });

        Schema::table('locals', function (Blueprint $table) {
            $table->decimal('prixlocal', 10, 2)->nullable()->change();
        });
    }
};