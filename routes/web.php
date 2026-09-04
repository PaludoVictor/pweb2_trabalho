<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/conta', [ContaController::class, 'index']);
Route::get('/conta/create', [ContaController::class, 'create']);
Route::post('/conta/store', [ContaController::class, 'store'])->name('conta.store');
Route::get('/conta/edit/{id}', [ContaController::class, 'edit'])->name('conta.edit');
Route::put('/conta/update/{id}', [ContaController::class, 'update'])->name('conta.update');
Route::delete('/conta/{id}', [ContaController::class, 'destroy'])->name('conta.destroy');
Route::post('/conta/search', [ContaController::class, 'search'])->name('conta.search');

Route::get('/categoria', [CategoriaController::class, 'index']);
Route::get('/categoria/create', [CategoriaController::class, 'create']);
Route::post('/categoria/store', [CategoriaController::class, 'store'])->name('categoria.store');
Route::get('/categoria/edit/{id}', [CategoriaController::class, 'edit'])->name('categoria.edit');
Route::put('/categoria/update/{id}', [CategoriaController::class, 'update'])->name('categoria.update');
Route::delete('/categoria/{id}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');
Route::post('/categoria/search', [CategoriaController::class, 'search'])->name('categoria.search');

Route::get('/transacao', [TransacaoController::class, 'index']);
Route::get('/transacao/create', [TransacaoController::class, 'create']);
Route::post('/transacao/store', [TransacaoController::class, 'store'])->name('transacao.store');
Route::get('/transacao/edit/{id}', [TransacaoController::class, 'edit'])->name('transacao.edit');
Route::put('/transacao/update/{id}', [TransacaoController::class, 'update'])->name('transacao.update');
Route::delete('/transacao/{id}', [TransacaoController::class, 'destroy'])->name('transacao.destroy');
Route::post('/transacao/search', [TransacaoController::class, 'search'])->name('transacao.search');

Route::get('/usuario', [UsuarioController::class, 'index']);
Route::get('/usuario/create', [UsuarioController::class, 'create']);
Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuario.store');
Route::get('/usuario/edit/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');
Route::put('/usuario/update/{id}', [UsuarioController::class, 'update'])->name('usuario.update');
Route::delete('/usuario/{id}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');
Route::post('/usuario/search', [UsuarioController::class, 'search'])->name('usuario.search');
