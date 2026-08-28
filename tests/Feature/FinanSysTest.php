<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Conta;
use App\Models\Categoria;
use App\Models\Transacao;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinanSysTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboard_renders_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Resumo Financeiro');
        $response->assertSee('Distribuição de Lançamentos por Categoria');
        $response->assertSee('Total Geral');
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Conta CRUD
    |--------------------------------------------------------------------------
    */
    public function test_conta_crud_operations()
    {
        // 1. List
        $response = $this->get('/conta');
        $response->assertStatus(200);
        $response->assertSee('Contas Bancárias');

        // 2. Create form
        $response = $this->get('/conta/create');
        $response->assertStatus(200);

        // 3. Store
        $contaData = [
            'nome_instituicao' => 'Banco C6',
            'agencia_numero' => '0001',
            'numero_conta' => '778899-0',
            'saldo_atual' => 3500.50,
        ];
        $response = $this->post(route('conta.store'), $contaData);
        $response->assertRedirect('/conta');
        $this->assertDatabaseHas('contas', ['numero_conta' => '778899-0']);

        // 4. Edit form
        $conta = Conta::where('numero_conta', '778899-0')->first();
        $response = $this->get(route('conta.edit', $conta->id));
        $response->assertStatus(200);

        // 5. Update
        $updateData = [
            'nome_instituicao' => 'Banco C6 Bank',
            'agencia_numero' => '0002',
            'numero_conta' => '778899-0',
            'saldo_atual' => 4000.00,
        ];
        $response = $this->put(route('conta.update', $conta->id), $updateData);
        $response->assertRedirect('/conta');
        $this->assertDatabaseHas('contas', ['nome_instituicao' => 'Banco C6 Bank']);

        // 6. Search
        $response = $this->post(route('conta.search'), [
            'tipo' => 'nome_instituicao',
            'valor' => 'C6 Bank',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Banco C6 Bank');

        // 7. Destroy
        $response = $this->delete(route('conta.destroy', $conta->id));
        $response->assertRedirect('/conta');
        $this->assertDatabaseMissing('contas', ['id' => $conta->id]);
    }

    public function test_conta_validation()
    {
        $response = $this->post(route('conta.store'), []);
        $response->assertSessionHasErrors(['nome_instituicao', 'agencia_numero', 'numero_conta', 'saldo_atual']);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Categoria CRUD
    |--------------------------------------------------------------------------
    */
    public function test_categoria_crud_operations()
    {
        // 1. List
        $response = $this->get('/categoria');
        $response->assertStatus(200);

        // 2. Store
        $catData = [
            'nome_categoria' => 'Viagens e Férias',
            'tipo_despesa' => 'Variável',
            'limite_orcamento' => 3000.00,
            'cor_identificacao' => '#6366f1',
        ];
        $response = $this->post(route('categoria.store'), $catData);
        $response->assertRedirect('/categoria');
        $this->assertDatabaseHas('categorias', ['nome_categoria' => 'Viagens e Férias']);

        $categoria = Categoria::where('nome_categoria', 'Viagens e Férias')->first();

        // 3. Edit & Update
        $response = $this->put(route('categoria.update', $categoria->id), [
            'nome_categoria' => 'Viagens Internacionais',
            'tipo_despesa' => 'Variável',
            'limite_orcamento' => 5000.00,
            'cor_identificacao' => '#6366f1',
        ]);
        $response->assertRedirect('/categoria');
        $this->assertDatabaseHas('categorias', ['nome_categoria' => 'Viagens Internacionais']);

        // 4. Search
        $response = $this->post(route('categoria.search'), [
            'tipo' => 'nome_categoria',
            'valor' => 'Viagens',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Viagens Internacionais');

        // 5. Destroy
        $response = $this->delete(route('categoria.destroy', $categoria->id));
        $response->assertRedirect('/categoria');
        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Transacao CRUD & Relationships
    |--------------------------------------------------------------------------
    */
    public function test_transacao_crud_and_relationships()
    {
        $conta = Conta::first();
        $categoria = Categoria::first();

        // 1. List
        $response = $this->get('/transacao');
        $response->assertStatus(200);

        // 2. Store
        $tData = [
            'descricao_movimento' => 'Manutenção Computador',
            'valor_transacao' => 320.00,
            'data_competencia' => '2026-08-20',
            'metodo_pagamento' => 'Pix',
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
        ];
        $response = $this->post(route('transacao.store'), $tData);
        $response->assertRedirect('/transacao');
        $this->assertDatabaseHas('transacoes', ['descricao_movimento' => 'Manutenção Computador']);

        $transacao = Transacao::where('descricao_movimento', 'Manutenção Computador')->first();
        $this->assertEquals($conta->nome_instituicao, $transacao->conta->nome_instituicao);
        $this->assertEquals($categoria->nome_categoria, $transacao->categoria->nome_categoria);

        // 3. Search
        $response = $this->post(route('transacao.search'), [
            'tipo' => 'descricao_movimento',
            'valor' => 'Computador',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Manutenção Computador');

        // 4. Destroy
        $response = $this->delete(route('transacao.destroy', $transacao->id));
        $response->assertRedirect('/transacao');
        $this->assertDatabaseMissing('transacoes', ['id' => $transacao->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Usuario CRUD & Auth
    |--------------------------------------------------------------------------
    */
    public function test_usuario_crud_and_auth()
    {
        // 1. Store user
        $uData = [
            'nome' => 'Lucas Teste',
            'telefone' => '(49) 97777-7777',
            'email' => 'lucas@teste.com',
            'login' => 'lucastest',
            'senha' => 'senha123',
        ];
        $response = $this->post(route('usuario.store'), $uData);
        $response->assertRedirect('/usuario');
        $this->assertDatabaseHas('usuarios', ['login' => 'lucastest']);

        // 2. Auth login
        $response = $this->post(route('login.post'), [
            'login' => 'lucastest',
            'senha' => 'senha123',
        ]);
        $response->assertRedirect('/');
        $this->assertTrue(session('usuario_logado') === true);

        // 3. Logout
        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');
        $this->assertNull(session('usuario_logado'));
    }
}
