@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Categoria' : 'Cadastrar Categoria')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('categoria.update', $data->id);
    } else {
        $action = route('categoria.store');
    }
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Categoria</h2>
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
                    <label>Nome da Categoria</label>
                    <input type="text" name="nome_categoria" class="form-control" value="{{ old('nome_categoria', $data->nome_categoria ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Tipo de Despesa</label>
                    <select name="tipo_despesa" class="form-control" required>
                        <option value="Fixa" {{ old('tipo_despesa', $data->tipo_despesa ?? '') == 'Fixa' ? 'selected' : '' }}>Fixa</option>
                        <option value="Variável" {{ old('tipo_despesa', $data->tipo_despesa ?? 'Variável') == 'Variável' ? 'selected' : '' }}>Variável</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Orçamento Limite (R$)</label>
                    <input type="number" step="0.01" name="limite_orcamento" class="form-control" value="{{ old('limite_orcamento', $data->limite_orcamento ?? '') }}" required>
                </div>
                <div class="col-md-2">
                    <label>Cor Identificação</label>
                    <input type="color" name="cor_identificacao" class="form-control form-control-color w-100" value="{{ old('cor_identificacao', $data->cor_identificacao ?? '#000000') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ url('categoria') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
