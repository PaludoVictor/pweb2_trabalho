@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Transação' : 'Cadastrar Transação')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('transacao.update', $data->id);
    } else {
        $action = route('transacao.store');
    }
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Transação</h2>
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
                    <label>Descrição do Movimento</label>
                    <input type="text" name="descricao_movimento" class="form-control" value="{{ old('descricao_movimento', $data->descricao_movimento ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Método de Pagamento</label>
                    <select name="metodo_pagamento" class="form-control" required>
                        @php $metodoAtual = old('metodo_pagamento', $data->metodo_pagamento ?? 'Pix'); @endphp
                        <option value="Pix" {{ $metodoAtual == 'Pix' ? 'selected' : '' }}>Pix</option>
                        <option value="Cartão de Crédito" {{ $metodoAtual == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                        <option value="Cartão de Débito" {{ $metodoAtual == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                        <option value="Dinheiro" {{ $metodoAtual == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                        <option value="Boleto" {{ $metodoAtual == 'Boleto' ? 'selected' : '' }}>Boleto</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Valor (R$)</label>
                    <input type="number" step="0.01" name="valor_transacao" class="form-control" value="{{ old('valor_transacao', $data->valor_transacao ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Data de Competência</label>
                    <input type="date" name="data_competencia" class="form-control" value="{{ old('data_competencia', $data->data_competencia ?? date('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Conta Bancária</label>
                    <select name="conta_id" class="form-control" required>
                        <option value="">Selecione a conta...</option>
                        @php $contaSelecionada = old('conta_id', $data->conta_id ?? ''); @endphp
                        @foreach ($contas as $c)
                            <option value="{{ $c->id }}" {{ $contaSelecionada == $c->id ? 'selected' : '' }}>
                                {{ $c->nome_instituicao }} ({{ $c->numero_conta }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Categoria</label>
                    <select name="categoria_id" class="form-control" required>
                        <option value="">Selecione a categoria...</option>
                        @php $catSelecionada = old('categoria_id', $data->categoria_id ?? ''); @endphp
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ $catSelecionada == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="{{ url('transacao') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
