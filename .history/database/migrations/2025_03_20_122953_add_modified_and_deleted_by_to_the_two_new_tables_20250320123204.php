<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        $tables = [
            'creditbancaires', 'actfinals'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('last_modified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'creditbancaires', 'actfinals'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign([$table . '_last_modified_by_foreign']);
                $table->dropForeign([$table . '_deleted_by_foreign']);
                $table->dropColumn(['last_modified_by', 'deleted_by']);
            });
        }
    }
};
