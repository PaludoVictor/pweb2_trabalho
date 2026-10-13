<?php

namespace Database\Seeders;

use App\Models\Cartao;
use App\Models\Conta;
use Illuminate\Database\Seeder;

class CartaoSeeder extends Seeder
{
    public function run(): void
    {
        $contas = Conta::all();

        if ($contas->count() > 0) {
            Cartao::firstOrCreate(
                ['nome_cartao' => 'Cartão Platinum Rewards'],
                [
                    'bandeira' => 'Mastercard',
                    'limite_credito' => 8500.00,
                    'dia_vencimento' => 10,
                    'imagem' => null,
                    'conta_id' => $contas[0]->id,
                ]
            );

            if ($contas->count() > 1) {
                Cartao::firstOrCreate(
                    ['nome_cartao' => 'Cartão Visa Infinite Cashback'],
                    [
                        'bandeira' => 'Visa',
                        'limite_credito' => 12000.00,
                        'dia_vencimento' => 15,
                        'imagem' => null,
                        'conta_id' => $contas[1]->id,
                    ]
                );
            }

            if ($contas->count() > 2) {
                Cartao::firstOrCreate(
                    ['nome_cartao' => 'Cartão Elo Mais'],
                    [
                        'bandeira' => 'Elo',
                        'limite_credito' => 3500.00,
                        'dia_vencimento' => 20,
                        'imagem' => null,
                        'conta_id' => $contas[2]->id,
                    ]
                );
            }
        }
    }
}

