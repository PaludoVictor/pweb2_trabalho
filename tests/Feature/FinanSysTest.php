<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Conta;
use App\Models\Categoria;
use App\Models\Transacao;
use App\Models\Meta;
use App\Models\Cartao;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $response->assertSee('Dashboard Financeiro');
        $response->assertSee('Gastos por Categoria');
        $response->assertSee('Metas Financeiras');
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Conta CRUD (1:N and 1:1)
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

        // 7. Destroy (without children)
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
    | Tests for Transacao CRUD, Upload & Reports
    |--------------------------------------------------------------------------
    */
    public function test_transacao_crud_upload_and_reports()
    {
        Storage::fake('public');

        $conta = Conta::first();
        $categoria = Categoria::first();

        // 1. List
        $response = $this->get('/transacao');
        $response->assertStatus(200);
        $response->assertSee('Gerenciar Transações');

        // 2. Store with image upload
        $fakeImage = UploadedFile::fake()->image('comprovante.jpg');
        $tData = [
            'descricao_movimento' => 'Manutenção Computador',
            'valor_transacao' => 320.00,
            'data_competencia' => '2026-08-20',
            'metodo_pagamento' => 'Pix',
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'imagem' => $fakeImage,
        ];
        $response = $this->post(route('transacao.store'), $tData);
        $response->assertRedirect('/transacao');
        $this->assertDatabaseHas('transacoes', ['descricao_movimento' => 'Manutenção Computador']);

        $transacao = Transacao::where('descricao_movimento', 'Manutenção Computador')->first();
        $this->assertNotNull($transacao->imagem);
        Storage::disk('public')->assertExists($transacao->imagem);

        // 3. Search
        $response = $this->post(route('transacao.search'), [
            'tipo' => 'descricao_movimento',
            'valor' => 'Computador',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Manutenção Computador');

        // 4. PDF Reports
        $response = $this->get(route('transacao.report'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));

        $response = $this->get(route('transacao.report.categorias'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));

        // 5. Pie Chart
        $response = $this->get(route('transacao.chart'));
        $response->assertStatus(200);
        $response->assertSee('Gráfico Financeiro (Formato Pizza)');

        // 6. Destroy deletes physical image file
        $imagePath = $transacao->imagem;
        $response = $this->delete(route('transacao.destroy', $transacao->id));
        $response->assertRedirect('/transacao');
        $this->assertDatabaseMissing('transacoes', ['id' => $transacao->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Meta CRUD (Novo CRUD 1), Upload & Bar Chart
    |--------------------------------------------------------------------------
    */
    public function test_meta_crud_upload_and_chart()
    {
        Storage::fake('public');

        $conta = Conta::first();
        $categoria = Categoria::first();

        // 1. List
        $response = $this->get('/meta');
        $response->assertStatus(200);
        $response->assertSee('Metas Financeiras');

        // 2. Store with Image Upload
        $fakeImage = UploadedFile::fake()->image('meta_carro.png');
        $metaData = [
            'titulo' => 'Comprar Carro Elétrico',
            'valor_meta' => 95000.00,
            'valor_atual' => 15000.00,
            'data_limite' => '2028-12-31',
            'conta_id' => $conta->id,
            'categoria_id' => $categoria->id,
            'imagem' => $fakeImage,
        ];
        $response = $this->post(route('meta.store'), $metaData);
        $response->assertRedirect('/meta');
        $this->assertDatabaseHas('metas', ['titulo' => 'Comprar Carro Elétrico']);

        $meta = Meta::where('titulo', 'Comprar Carro Elétrico')->first();
        $this->assertNotNull($meta->imagem);
        Storage::disk('public')->assertExists($meta->imagem);

        // 3. Search
        $response = $this->post(route('meta.search'), [
            'tipo' => 'titulo',
            'valor' => 'Elétrico',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Comprar Carro Elétrico');

        // 4. Bar Chart & PDF Report
        $response = $this->get(route('meta.chart'));
        $response->assertStatus(200);

        $response = $this->get(route('meta.report'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));

        // 5. Destroy deletes physical image
        $imagePath = $meta->imagem;
        $response = $this->delete(route('meta.destroy', $meta->id));
        $response->assertRedirect('/meta');
        $this->assertDatabaseMissing('metas', ['id' => $meta->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    /*
    |--------------------------------------------------------------------------
    | Tests for Cartao CRUD (Novo CRUD 2) & Upload
    |--------------------------------------------------------------------------
    */
    public function test_cartao_crud_and_upload()
    {
        Storage::fake('public');

        $conta = Conta::first();

        // 1. List
        $response = $this->get('/cartao');
        $response->assertStatus(200);
        $response->assertSee('Cartões de Crédito');

        // 2. Store with Image Upload
        $fakeImage = UploadedFile::fake()->image('cartao_black.jpg');
        $cartaoData = [
            'nome_cartao' => 'Cartão Infinite Empresarial',
            'bandeira' => 'Visa',
            'limite_credito' => 25000.00,
            'dia_vencimento' => 20,
            'conta_id' => $conta->id,
            'imagem' => $fakeImage,
        ];
        $response = $this->post(route('cartao.store'), $cartaoData);
        $response->assertRedirect('/cartao');
        $this->assertDatabaseHas('cartoes', ['nome_cartao' => 'Cartão Infinite Empresarial']);

        $cartao = Cartao::where('nome_cartao', 'Cartão Infinite Empresarial')->first();
        $this->assertNotNull($cartao->imagem);
        Storage::disk('public')->assertExists($cartao->imagem);

        // 3. Search
        $response = $this->post(route('cartao.search'), [
            'tipo' => 'nome_cartao',
            'valor' => 'Empresarial',
        ]);
        $response->assertStatus(200);
        $response->assertSee('Cartão Infinite Empresarial');

        // 4. Destroy deletes physical image
        $imagePath = $cartao->imagem;
        $response = $this->delete(route('cartao.destroy', $cartao->id));
        $response->assertRedirect('/cartao');
        $this->assertDatabaseMissing('cartoes', ['id' => $cartao->id]);
        Storage::disk('public')->assertMissing($imagePath);
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
