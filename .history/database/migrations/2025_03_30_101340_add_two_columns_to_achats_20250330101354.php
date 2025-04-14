<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->decimal('apport_personel', 15, 2)->nullable()->after('Numlocal');
            $table->decimal('credit_bancaire', 15, 2)->nullable()->after('apport_personel');
        });
    }

    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->dropColumn(['apport_personel', 'credit_bancaire']);
        });
    }
};