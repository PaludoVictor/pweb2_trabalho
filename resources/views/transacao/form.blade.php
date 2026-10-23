@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Transação' : 'Cadastrar Transação')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('transacao.update', $data->id);
    } else {
        $action = route('transacao.store');
    }
    $url_imagem = !empty($data->imagem) ? asset('storage/' . $data->imagem) : asset('images/sem_imagem.svg');
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Transação</h2>
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
                    <label class="form-label">Descrição do Movimento</label>
                    <input type="text" name="descricao_movimento" class="form-control" value="{{ old('descricao_movimento', $data->descricao_movimento ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Método de Pagamento</label>
                    <select name="metodo_pagamento" class="form-select" required>
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
                    <label class="form-label">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor_transacao" class="form-control" value="{{ old('valor_transacao', $data->valor_transacao ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data de Competência</label>
                    <input type="date" name="data_competencia" class="form-control" value="{{ old('data_competencia', $data->data_competencia ?? date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Observação (Opcional)</label>
                    <input type="text" name="observacao" class="form-control" value="{{ old('observacao', $data->observacao ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Conta Bancária</label>
                    <select name="conta_id" class="form-select" required>
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
                    <label class="form-label">Categoria</label>
                    <select name="categoria_id" class="form-select" required>
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

            {{-- Upload de Comprovante (Padrão Aluno de pweb2_2026_2) --}}
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <label class="form-label d-block">Comprovante Atual</label>
                    <img src="{{ $url_imagem }}" class="img-thumbnail rounded shadow-sm" width="120" height="120" style="object-fit: cover;" alt="Comprovante">
                </div>
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <label class="form-label">Anexar Comprovante / Recibo (PNG, JPG ou JPEG)</label>
                    <input type="file" name="imagem" class="form-control">
                    <div class="form-text">Envie a foto ou captura do comprovante bancário ou nota fiscal.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Salvar</button>
            <a href="{{ url('transacao') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
