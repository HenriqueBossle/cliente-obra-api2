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
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->string('construction_name')->nullable(); // Nome da obra
            $table->string('builder_name')->nullable(); // Nome construtor
            $table->string('builder_phone')->nullable();
            $table->string('cpf_cnpj')->nullable(); // CPF ou CNPJ do construtor
            $table->string('sitemanager_name')->nullable();
            $table->string('sitemanager_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('type')->nullable(); // Tipo da obra
            $table->string('status')->nullable(); // Em andamento, finalizada, etc.
            $table->decimal('volume', 8, 2)->nullable(); // Volume (ex: 120.50 m3)
            $table->date('date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constructions');
    }
};
