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
        Schema::create('actfinals', function (Blueprint $table) {
            $table->id();

            $table->string('codeclient')->nullable();
            $table->unsignedBigInteger('codebanque')->nullable();

            $table->boolean('depotcahierplusattestremisecles')->default(0);
            $table->timestamp('depotcahierplusattestremisecles_attributed_at')->nullable();
            $table->timestamp('depotcahierplusattestremisecles_modified_at')->nullable();

            $table->boolean('signactfinal')->default(0);
            $table->timestamp('signactfinal_attributed_at')->nullable();
            $table->timestamp('signactfinal_modified_at')->nullable();

            $table->boolean('enrgactfinal')->default(0);
            $table->timestamp('enrgactfinal_attributed_at')->nullable();
            $table->timestamp('enrgactfinal_modified_at')->nullable();

            $table->boolean('remisedescles')->default(0);
            $table->timestamp('remisedescles_attributed_at')->nullable();
            $table->timestamp('remisedescles_modified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('codeclient')->references('codeclient')->on('clients');
            $table->foreign('codebanque')->references('codebanque')->on('banques');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actfinals');

        Schema::table('actfinals', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // Removes 'deleted_at' column if rolled back
        });
    }
};
