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
        Schema::create('creditbancaires', function (Blueprint $table) {
            $table->id();

            $table->boolean('depotdossier')->default(0);
            $table->timestamp('depotdossier_attributed_at')->nullable();
            $table->timestamp('depotdossier_modified_at')->nullable();

            $table->boolean('comite')->default(0);
            $table->timestamp('comite_attributed_at')->nullable();
            $table->timestamp('comite_modified_at')->nullable();

            $table->boolean('paiementfrais')->default(0);
            $table->timestamp('paiementfrais_attributed_at')->nullable();
            $table->timestamp('paiementfrais_modified_at')->nullable();

            $table->boolean('signatureconvenrg')->default(0);
            $table->timestamp('signatureconvenrg_attributed_at')->nullable();
            $table->timestamp('signatureconvenrg_modified_at')->nullable();

            $table->boolean('dossiertransfnotaire')->default(0);
            $table->timestamp('dossiertransfnotaire_attributed_at')->nullable();
            $table->timestamp('dossiertransfnotaire_modified_at')->nullable();

            $table->boolean('signvspclientgerant')->default(0);
            $table->timestamp('signvspclientgerant_attributed_at')->nullable();
            $table->timestamp('signvspclientgerant_modified_at')->nullable();

            $table->boolean('recuperationcheque')->default(0);
            $table->timestamp('recuperationcheque_attributed_at')->nullable();
            $table->timestamp('recuperationcheque_modified_at')->nullable();

            $table->boolean('enrgvsp')->default(0);
            $table->timestamp('enrgvsp_attributed_at')->nullable();
            $table->timestamp('enrgvsp_modified_at')->nullable();

            $table->boolean('publicationvsp')->default(0);
            $table->timestamp('publicationvsp_attributed_at')->nullable();
            $table->timestamp('publicationvsp_modified_at')->nullable();

            $table->boolean('paiementtranches')->default(0);
            $table->timestamp('paiementtranches_attributed_at')->nullable();
            $table->timestamp('paiementtranches_modified_at')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditbancaires');

        Schema::table('creditbancaires', function (Blueprint $table) {
            $table->dropColumn('deleted_at'); // Removes 'deleted_at' column if rolled back
        });

    }
};
