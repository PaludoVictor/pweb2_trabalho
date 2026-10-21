<?php

namespace Database\Seeders;

use App\Models\Meta;
use App\Models\Conta;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class MetaSeeder extends Seeder
{
    public function run(): void
    {
        $conta1 = Conta::first();
        $conta2 = Conta::skip(1)->first() ?? $conta1;
        $catReserva = Categoria::where('nome_categoria', 'like', '%Moradia%')->first() ?? Categoria::first();
        $catLazer = Categoria::where('nome_categoria', 'like', '%Lazer%')->first() ?? Categoria::first();
        $catEstudos = Categoria::where('nome_categoria', 'like', '%Estudos%')->first() ?? Categoria::first();

        if ($conta1 && $catReserva) {
            Meta::firstOrCreate(
                ['titulo' => 'Reserva de Emergência 6 Meses'],
                [
                    'valor_meta' => 12000.00,
                    'valor_atual' => 5500.00,
                    'data_limite' => date('Y-m-d', strtotime('+10 months')),
                    'conta_id' => $conta1->id,
                    'categoria_id' => $catReserva->id,
                ]
            );

            Meta::firstOrCreate(
                ['titulo' => 'Viagem de Férias para a Praia'],
                [
                    'valor_meta' => 4500.00,
                    'valor_atual' => 3200.00,
                    'data_limite' => date('Y-m-d', strtotime('+4 months')),
                    'conta_id' => $conta2 ? $conta2->id : $conta1->id,
                    'categoria_id' => $catLazer ? $catLazer->id : $catReserva->id,
                ]
            );

            Meta::firstOrCreate(
                ['titulo' => 'Notebook Novo para Estudos'],
                [
                    'valor_meta' => 6000.00,
                    'valor_atual' => 4800.00,
                    'data_limite' => date('Y-m-d', strtotime('+6 months')),
                    'conta_id' => $conta1->id,
                    'categoria_id' => $catEstudos ? $catEstudos->id : $catReserva->id,
                ]
            );
        }
    }
}
