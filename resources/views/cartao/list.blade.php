@extends('main')

@section('titulo', 'Cartões de Crédito - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Cartões de Crédito</h2>
    <a href="{{ url('cartao/create') }}" class="btn btn-success">+ Novo Cartão</a>
</div>

<form action="{{ route('cartao.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="nome_cartao">Nome do Cartão</option>
                <option value="bandeira">Bandeira</option>
                <option value="conta">Conta Vinculada</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="valor" class="form-control" placeholder="Pesquisar cartão...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i> Pesquisar</button>
            <a href="{{ url('cartao') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto/Cartão</th>
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
                    $nome_imagem = !empty($item->imagem) ? $item->imagem : 'sem_imagem.jpg';
                @endphp
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="/storage/{{ $nome_imagem }}" class="rounded shadow-sm" width="45" height="45" style="object-fit: cover;" alt="Cartão">
                    </td>
                    <td class="fw-semibold">{{ $item->nome_cartao }}</td>
                    <td>
                        <span class="badge bg-dark"><i class="fa-regular fa-credit-card me-1"></i> {{ $item->bandeira }}</span>
                    </td>
                    <td>{{ $item->conta->nome_instituicao ?? 'N/A' }} ({{ $item->conta->numero_conta ?? '' }})</td>
                    <td class="fw-bold text-primary">R$ {{ number_format($item->limite_credito, 2, ',', '.') }}</td>
                    <td>Todo dia <strong>{{ $item->dia_vencimento }}</strong></td>
                    <td>
                        <a href="{{ route('cartao.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('cartao.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este cartão?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-3 text-muted">Nenhum cartão de crédito cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

