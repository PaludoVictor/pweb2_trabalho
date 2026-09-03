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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('cpf', 14)->nullable()->after('nome');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->text('descricao')->nullable()->after('nome_categoria');
        });

        Schema::table('contas', function (Blueprint $table) {
            $table->string('tipo_conta', 50)->nullable()->after('nome_instituicao'); // ex: Corrente, Poupança
        });

        Schema::table('transacoes', function (Blueprint $table) {
            $table->text('observacao')->nullable()->after('descricao_movimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('cpf');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });

        Schema::table('contas', function (Blueprint $table) {
            $table->dropColumn('tipo_conta');
        });

        Schema::table('transacoes', function (Blueprint $table) {
            $table->dropColumn('observacao');
        });
    }
};
