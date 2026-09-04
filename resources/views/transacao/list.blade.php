@extends('main')

@section('titulo', 'Gerenciar Transações - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Transações</h2>
    <a href="{{ url('transacao/create') }}" class="btn btn-primary">+ Nova Transação</a>
</div>

<form action="{{ route('transacao.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-8">
            <input type="text" name="valor" class="form-control" placeholder="Buscar transação por descrição...">
        </div>
        <div class="col-md-4">
            <button class="btn btn-outline-secondary" type="submit">Pesquisar</button>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Conta</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>Método</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $t)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($t->data_competencia)) }}</td>
                    <td>{{ $t->descricao_movimento }}</td>
                    <td>{{ $t->conta->nome_instituicao ?? 'N/A' }} ({{ $t->conta->numero_conta ?? '' }})</td>
                    <td>
                        @if (isset($t->categoria))
                            <span class="badge" style="background-color: {{ $t->categoria->cor_identificacao }};">
                                {{ $t->categoria->nome_categoria }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Sem Categoria</span>
                        @endif
                    </td>
                    <td class="fw-bold">R$ {{ number_format($t->valor_transacao, 2, ',', '.') }}</td>
                    <td>{{ $t->metodo_pagamento }}</td>
                    <td>
                        <a href="{{ route('transacao.edit', $t->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('transacao.destroy', $t->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">Nenhuma transação encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
