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
        Usuario::updateOrCreate(
            ['login' => 'admin'],
            [
                'nome' => 'Administrador',
                'cpf' => '529.982.247-25',
                'imagem' => null,
                'telefone' => '(49) 99999-9999',
                'email' => 'admin@finansys.com',
                'login' => 'admin',
                'senha' => '123',
            ]
        );
    }
}
