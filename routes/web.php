<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

// Autenticação e Sessão
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// CRUD Conta
Route::get('/conta/report', [ContaController::class, 'report'])->name('conta.report');
Route::get('/conta', [ContaController::class, 'index']);
Route::get('/conta/create', [ContaController::class, 'create']);
Route::post('/conta/store', [ContaController::class, 'store'])->name('conta.store');
Route::get('/conta/edit/{id}', [ContaController::class, 'edit'])->name('conta.edit');
Route::put('/conta/update/{id}', [ContaController::class, 'update'])->name('conta.update');
Route::delete('/conta/{id}', [ContaController::class, 'destroy'])->name('conta.destroy');
Route::post('/conta/search', [ContaController::class, 'search'])->name('conta.search');

// CRUD Categoria
Route::get('/categoria', [CategoriaController::class, 'index']);
Route::get('/categoria/create', [CategoriaController::class, 'create']);
Route::post('/categoria/store', [CategoriaController::class, 'store'])->name('categoria.store');
Route::get('/categoria/edit/{id}', [CategoriaController::class, 'edit'])->name('categoria.edit');
Route::put('/categoria/update/{id}', [CategoriaController::class, 'update'])->name('categoria.update');
Route::delete('/categoria/{id}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');
Route::post('/categoria/search', [CategoriaController::class, 'search'])->name('categoria.search');

// Relatórios PDF e Gráficos de Transação (devem vir antes dos parâmetros {id} para não colidir)
Route::get('/transacao/report', [TransacaoController::class, 'report'])->name('transacao.report');
Route::get('/transacao/report-categorias', [TransacaoController::class, 'reportCategorias'])->name('transacao.report.categorias');
Route::get('/transacao/chart', [TransacaoController::class, 'chart'])->name('transacao.chart');

// CRUD Transação
Route::get('/transacao', [TransacaoController::class, 'index']);
Route::get('/transacao/create', [TransacaoController::class, 'create']);
Route::post('/transacao/store', [TransacaoController::class, 'store'])->name('transacao.store');
Route::get('/transacao/edit/{id}', [TransacaoController::class, 'edit'])->name('transacao.edit');
Route::put('/transacao/update/{id}', [TransacaoController::class, 'update'])->name('transacao.update');
Route::delete('/transacao/{id}', [TransacaoController::class, 'destroy'])->name('transacao.destroy');
Route::post('/transacao/search', [TransacaoController::class, 'search'])->name('transacao.search');

// Gráfico de Metas
Route::get('/meta/chart', [MetaController::class, 'chart'])->name('meta.chart');

// CRUD Metas Financeiras (Novo CRUD 1)
Route::get('/meta', [MetaController::class, 'index']);
Route::get('/meta/create', [MetaController::class, 'create']);
Route::post('/meta/store', [MetaController::class, 'store'])->name('meta.store');
Route::get('/meta/edit/{id}', [MetaController::class, 'edit'])->name('meta.edit');
Route::put('/meta/update/{id}', [MetaController::class, 'update'])->name('meta.update');
Route::delete('/meta/{id}', [MetaController::class, 'destroy'])->name('meta.destroy');
Route::post('/meta/search', [MetaController::class, 'search'])->name('meta.search');

// CRUD Cartões de Crédito (Novo CRUD 2)
Route::get('/cartao', [CartaoController::class, 'index']);
Route::get('/cartao/create', [CartaoController::class, 'create']);
Route::post('/cartao/store', [CartaoController::class, 'store'])->name('cartao.store');
Route::get('/cartao/edit/{id}', [CartaoController::class, 'edit'])->name('cartao.edit');
Route::put('/cartao/update/{id}', [CartaoController::class, 'update'])->name('cartao.update');
Route::delete('/cartao/{id}', [CartaoController::class, 'destroy'])->name('cartao.destroy');
Route::post('/cartao/search', [CartaoController::class, 'search'])->name('cartao.search');

// CRUD Usuário
Route::get('/usuario', [UsuarioController::class, 'index']);
Route::get('/usuario/create', [UsuarioController::class, 'create']);
Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuario.store');
Route::get('/usuario/edit/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');
Route::put('/usuario/update/{id}', [UsuarioController::class, 'update'])->name('usuario.update');
Route::delete('/usuario/{id}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');
Route::post('/usuario/search', [UsuarioController::class, 'search'])->name('usuario.search');
