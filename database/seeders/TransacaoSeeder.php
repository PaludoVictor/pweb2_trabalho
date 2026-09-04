<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transacao;
use App\Models\Conta;
use App\Models\Categoria;

class TransacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contas = Conta::all();
        $categorias = Categoria::all();

        if ($contas->isEmpty() || $categorias->isEmpty()) {
            return;
        }

        Transacao::factory()->count(15)->create([
            'conta_id' => fn () => $contas->random()->id,
            'categoria_id' => fn () => $categorias->random()->id,
        ]);
    }
}
