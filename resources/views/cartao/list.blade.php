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
        <div class="col-md-9">
            <input type="text" name="valor" class="form-control" placeholder="Buscar cartão por nome...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
            <a href="{{ url('cartao') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
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
                    $nome_imagem = !empty($item->imagem) ? $item->imagem : 'sem_imagem.jpg';
                @endphp
                <tr>
                    <td>
                        <img src="/storage/{{ $nome_imagem }}" width="40" height="40" style="object-fit: cover; border-radius: 4px;" alt="Foto">
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
@stop
