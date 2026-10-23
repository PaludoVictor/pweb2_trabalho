@extends('main')

@section('titulo', 'Cartões de Crédito - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Cartões de Crédito</h2>
    <a href="{{ url('cartao/create') }}" class="btn btn-primary">+ Novo Cartão</a>
</div>

<form action="{{ route('cartao.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-lg-4">
            <input type="text" name="valor" class="form-control" value="{{ request('valor') }}" placeholder="Buscar cartão por nome...">
        </div>
        <div class="col-lg-4">
            <select name="conta_id" class="form-select" aria-label="Filtrar por conta">
                <option value="">Todas as contas</option>
                @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}" @selected(request('conta_id') == $conta->id)>{{ $conta->nome_instituicao }} — {{ $conta->numero_conta }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
            <a href="{{ url('cartao') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome do Cartão</th>
                    <th>Bandeira</th>
                    <th>Conta Vinculada</th>
                    <th>Limite de Crédito</th>
                    <th>Dia de Vencimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $item)
                @php
                    $url_imagem = !empty($item->imagem) ? asset('storage/' . $item->imagem) : asset('images/sem_imagem.svg');
                @endphp
                <tr>
                    <td>
                        <img src="{{ $url_imagem }}" width="40" height="40" style="object-fit: cover; border-radius: 4px;" alt="Foto">
                    </td>
                    <td>{{ $item->nome_cartao }}</td>
                    <td>{{ $item->bandeira }}</td>
                    <td>{{ $item->conta->nome_instituicao ?? 'N/A' }} ({{ $item->conta->numero_conta ?? '' }})</td>
                    <td>R$ {{ number_format($item->limite_credito, 2, ',', '.') }}</td>
                    <td>{{ $item->dia_vencimento }}</td>
                    <td>
                        <a href="{{ route('cartao.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('cartao.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">Nenhum cartão de crédito cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop
