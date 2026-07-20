<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('constructions', function (Blueprint $table) {
            // 1. Renomear o campo existente
            $table->renameColumn('volume', 'concrete_volume');

            // 2. Adicionar o novo campo (exemplo de campo string/varchar)
            $table->decimal('mortar_volume', 8, 2)->nullable()->after('concrete_volume');
            
        });
    }

    public function down(): void
    {
        Schema::table('constructions', function (Blueprint $table) {
            // Reverter as alterações no caso de um 'migrate:rollback'
            $table->renameColumn('concrete_volume', 'volume');
            $table->dropColumn('mortar_volume');
        });
    }
};