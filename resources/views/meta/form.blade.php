@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Meta Financeira' : 'Cadastrar Meta Financeira')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('meta.update', $data->id);
    } else {
        $action = route('meta.store');
    }
    $nome_imagem = !empty($data->imagem) ? $data->imagem : 'sem_imagem.jpg';
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Meta Financeira</h2>
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
                <div class="col-md-8">
                    <label class="form-label">Título da Meta</label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $data->titulo ?? '') }}" placeholder="Ex: Reserva de Emergência, Viagem, Carro Novo" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Data Limite / Prazo</label>
                    <input type="date" name="data_limite" class="form-control" value="{{ old('data_limite', $data->data_limite ?? '') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Valor Alvo da Meta (R$)</label>
                    <input type="number" step="0.01" name="valor_meta" class="form-control" value="{{ old('valor_meta', $data->valor_meta ?? '') }}" placeholder="0,00" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Valor Já Guardado / Atual (R$)</label>
                    <input type="number" step="0.01" name="valor_atual" class="form-control" value="{{ old('valor_atual', $data->valor_atual ?? '0.00') }}" placeholder="0,00">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Conta Bancária de Destino</label>
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
                    <label class="form-label">Categoria de Planejamento</label>
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

            {{-- Upload de Imagem/Foto da Meta (Padrão Aluno de pweb2_2026_2) --}}
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <label class="form-label d-block">Foto / Ícone Atual</label>
                    <img src="/storage/{{ $nome_imagem }}" class="img-thumbnail rounded shadow-sm" width="120" height="120" style="object-fit: cover;" alt="Imagem da Meta">
                </div>
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <label class="form-label">Foto / Imagem da Meta (PNG, JPG ou JPEG)</label>
                    <input type="file" name="imagem" class="form-control">
                    <div class="form-text">Adicione uma imagem inspiradora para seu objetivo financeiro.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Salvar</button>
            <a href="{{ url('meta') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop

