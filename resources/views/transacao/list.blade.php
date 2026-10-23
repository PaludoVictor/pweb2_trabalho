@extends('main')

@section('titulo', 'Gerenciar Transações - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Transações</h2>
    <div>
        <a href="{{ url('transacao/create') }}" class="btn btn-success">Nova Transação</a>
        <a href="{{ route('transacao.report.categorias') }}" class="btn btn-danger">Relatório</a>
        <a href="{{ route('transacao.chart') }}" class="btn btn-warning">Gráfico</a>
    </div>
</div>

<form action="{{ route('transacao.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-lg-3">
            <input type="text" name="valor" class="form-control" value="{{ request('valor') }}" placeholder="Buscar transação por descrição...">
        </div>
        <div class="col-lg-3">
            <select name="conta_id" class="form-select" aria-label="Filtrar por conta">
                <option value="">Todas as contas</option>
                @foreach ($contas as $conta)
                    <option value="{{ $conta->id }}" @selected(request('conta_id') == $conta->id)>{{ $conta->nome_instituicao }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <select name="categoria_id" class="form-select" aria-label="Filtrar por categoria">
                <option value="">Todas as categorias</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria_id') == $categoria->id)>{{ $categoria->nome_categoria }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
            <a href="{{ url('transacao') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Comprovante</th>
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
                    <td><img src="{{ $t->imagem ? asset('storage/' . $t->imagem) : asset('images/sem_imagem.svg') }}" width="40" height="40" style="object-fit: cover; border-radius: 4px;" alt="Comprovante"></td>
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
                    <td colspan="8" class="text-center py-3 text-muted">Nenhuma transação encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop
