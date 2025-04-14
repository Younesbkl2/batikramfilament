<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdAttestationToAchatsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            // Add the ID_ATTESTATION column
            $table->unsignedBigInteger('ID_ATTESTATION')->nullable()->after('Observations');

            // Add the foreign key constraint
            $table->foreign('ID_ATTESTATION')->references('ID_ATTESTATION')->on('attestations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['ID_ATTESTATION']);

            // Drop the ID_ATTESTATION column
            $table->dropColumn('ID_ATTESTATION');
        });
    }
}
