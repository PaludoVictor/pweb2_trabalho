@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Usuário' : 'Cadastrar Usuário')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('usuario.update', $data->id);
    } else {
        $action = route('usuario.store');
    }
    $url_imagem = !empty($data->imagem) ? asset('storage/' . $data->imagem) : asset('images/sem_imagem.svg');
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Usuário</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (!empty($data->id))
                @method('PUT')
            @endif

            <input type="hidden" name="id" value="{{ old('id', $data->id ?? '') }}">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome', $data->nome ?? '') }}" placeholder="Ex: João da Silva" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $data->cpf ?? '') }}" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $data->telefone ?? '') }}" placeholder="(00) 00000-0000" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $data->email ?? '') }}" placeholder="usuario@email.com" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Login</label>
                    <input type="text" name="login" class="form-control" value="{{ old('login', $data->login ?? '') }}" placeholder="usuario" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Senha {{ !empty($data->id) ? '(Deixe em branco para não alterar)' : '' }}</label>
                    <input type="password" name="senha" class="form-control" minlength="8" {{ empty($data->id) ? 'required' : '' }} placeholder="Senha de acesso">
                </div>
            </div>

            {{-- Upload de Foto/Imagem do Usuário (Padrão Aluno de pweb2_2026_2) --}}
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <label class="form-label d-block">Foto Atual</label>
                    <img src="{{ $url_imagem }}" class="rounded-circle shadow-sm" width="120" height="120" style="object-fit: cover; border: 3px solid #e5e7eb;" alt="Foto do Usuário">
                </div>
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <label class="form-label">Foto de Perfil (PNG, JPG ou JPEG)</label>
                    <input type="file" name="imagem" class="form-control">
                    <div class="form-text">Envie uma foto para o perfil do usuário no sistema.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Salvar</button>
            <a href="{{ url('usuario') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
