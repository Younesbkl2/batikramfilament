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
        Schema::create('examples', function (Blueprint $table) {
            $table->id();
            $table->boolean('feature_one')->default(0);
            $table->timestamp('feature_one_attributed_at')->nullable();
            $table->timestamp('feature_one_modified_at')->nullable();
            
            $table->boolean('feature_two')->default(0);
            $table->timestamp('feature_two_attributed_at')->nullable();
            $table->timestamp('feature_two_modified_at')->nullable();
    
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examples');
        Schema::table('examples', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // Removes 'deleted_at' column if rolled back
        });
    }
};
