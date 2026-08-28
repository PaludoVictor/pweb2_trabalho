@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Conta Bancária' : 'Cadastrar Conta Bancária')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('conta.update', $data->id);
    } else {
        $action = route('conta.store');
    }
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Conta Bancária</h2>
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
                    <label>Instituição Financeira</label>
                    <input type="text" name="nome_instituicao" class="form-control" value="{{ old('nome_instituicao', $data->nome_instituicao ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label>Agência</label>
                    <input type="text" name="agencia_numero" class="form-control" value="{{ old('agencia_numero', $data->agencia_numero ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label>Número da Conta</label>
                    <input type="text" name="numero_conta" class="form-control" value="{{ old('numero_conta', $data->numero_conta ?? '') }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Saldo Atual (R$)</label>
                    <input type="number" step="0.01" name="saldo_atual" class="form-control" value="{{ old('saldo_atual', $data->saldo_atual ?? '') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ url('conta') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
