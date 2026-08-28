<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conta;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contas = [
            [
                'nome_instituicao' => 'Banco do Brasil',
                'agencia_numero' => '1234',
                'numero_conta' => '5678-9',
                'saldo_atual' => 4500.00
            ],
            [
                'nome_instituicao' => 'Itaú Unibanco',
                'agencia_numero' => '0002',
                'numero_conta' => '123123-1',
                'saldo_atual' => 8200.50
            ],
            [
                'nome_instituicao' => 'Nubank',
                'agencia_numero' => '0001',
                'numero_conta' => '987654-3',
                'saldo_atual' => 3150.75
            ],
            [
                'nome_instituicao' => 'Inter',
                'agencia_numero' => '0001',
                'numero_conta' => '554433-2',
                'saldo_atual' => 12300.00
            ],
        ];

        foreach ($contas as $conta) {
            Conta::firstOrCreate(['numero_conta' => $conta['numero_conta']], $conta);
        }
    }
}
