<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['login' => 'admin'],
            [
                'nome' => 'Administrador',
                'telefone' => '(49) 99999-9999',
                'email' => 'admin@finansys.com',
                'login' => 'admin',
                'senha' => '123', // Padrão usado no PWEB1
            ]
        );

        Usuario::firstOrCreate(
            ['login' => 'joao'],
            [
                'nome' => 'João Silva',
                'telefone' => '(49) 98888-8888',
                'email' => 'joao@finansys.com',
                'login' => 'joao',
                'senha' => '123456',
            ]
        );
    }
}
