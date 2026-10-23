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
        Schema::create('cartoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cartao', 100);
            $table->string('bandeira', 50);
            $table->decimal('limite_credito', 12, 2);
            $table->integer('dia_vencimento');
            $table->string('imagem', 150)->nullable();
            $table->foreignId('conta_id')->unique()->constrained('contas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartoes');
    }
};

