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
        Conta::factory()->count(5)->create();
    }
}
