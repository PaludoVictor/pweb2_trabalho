@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Usuário' : 'Cadastrar Usuário')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('usuario.update', $data->id);
    } else {
        $action = route('usuario.store');
    }
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Usuário</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ $action }}" method="POST">
            @csrf
            @if (!empty($data->id))
                @method('PUT')
            @endif

            <input type="hidden" name="id" value="{{ old('id', $data->id ?? '') }}">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome', $data->nome ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $data->telefone ?? '') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $data->email ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Login</label>
                    <input type="text" name="login" class="form-control" value="{{ old('login', $data->login ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Senha {{ !empty($data->id) ? '(Deixe em branco para não alterar)' : '' }}</label>
                    <input type="password" name="senha" class="form-control" {{ empty($data->id) ? 'required' : '' }}>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ url('usuario') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
