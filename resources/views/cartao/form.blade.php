@extends('main')

@section('titulo', !empty($data->id) ? 'Editar Cartão de Crédito' : 'Cadastrar Cartão de Crédito')

@section('conteudo')
@php
    if (!empty($data->id)) {
        $action = route('cartao.update', $data->id);
    } else {
        $action = route('cartao.store');
    }
    $nome_imagem = !empty($data->imagem) ? $data->imagem : 'sem_imagem.jpg';
@endphp

<div class="mb-4">
    <h2>{{ !empty($data->id) ? 'Editar' : 'Cadastrar' }} Cartão de Crédito</h2>
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
                    <label class="form-label">Nome de Identificação do Cartão</label>
                    <input type="text" name="nome_cartao" class="form-control" value="{{ old('nome_cartao', $data->nome_cartao ?? '') }}" placeholder="Ex: Cartão Platinum, Cartão Black, Cartão Inter" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bandeira</label>
                    <select name="bandeira" class="form-select" required>
                        @php $bandeiraAtual = old('bandeira', $data->bandeira ?? 'Mastercard'); @endphp
                        <option value="Mastercard" {{ $bandeiraAtual == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                        <option value="Visa" {{ $bandeiraAtual == 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="Elo" {{ $bandeiraAtual == 'Elo' ? 'selected' : '' }}>Elo</option>
                        <option value="American Express" {{ $bandeiraAtual == 'American Express' ? 'selected' : '' }}>American Express</option>
                        <option value="Hipercard" {{ $bandeiraAtual == 'Hipercard' ? 'selected' : '' }}>Hipercard</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Limite de Crédito (R$)</label>
                    <input type="number" step="0.01" name="limite_credito" class="form-control" value="{{ old('limite_credito', $data->limite_credito ?? '') }}" placeholder="0,00" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dia do Vencimento da Fatura</label>
                    <input type="number" min="1" max="31" name="dia_vencimento" class="form-control" value="{{ old('dia_vencimento', $data->dia_vencimento ?? '10') }}" placeholder="Ex: 10" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Conta Bancária Associada</label>
                    <select name="conta_id" class="form-select" required>
                        <option value="">Selecione a conta bancária vinculada...</option>
                        @php $contaSelecionada = old('conta_id', $data->conta_id ?? ''); @endphp
                        @foreach ($contas as $c)
                            <option value="{{ $c->id }}" {{ $contaSelecionada == $c->id ? 'selected' : '' }}>
                                {{ $c->nome_instituicao }} - Agência: {{ $c->agencia_numero }} | Conta: {{ $c->numero_conta }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Upload de Imagem/Foto do Cartão (Padrão Aluno de pweb2_2026_2) --}}
            <div class="row mb-4">
                <div class="col-md-3 text-center">
                    <label class="form-label d-block">Foto / Frente do Cartão</label>
                    <img src="/storage/{{ $nome_imagem }}" class="img-thumbnail rounded shadow-sm" width="120" height="120" style="object-fit: cover;" alt="Imagem do Cartão">
                </div>
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <label class="form-label">Foto / Imagem do Cartão ou Logo (PNG, JPG ou JPEG)</label>
                    <input type="file" name="imagem" class="form-control">
                    <div class="form-text">Adicione uma foto ilustrativa do cartão ou fatura digital.</div>
                </div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check me-1"></i> Salvar</button>
            <a href="{{ url('cartao') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop

