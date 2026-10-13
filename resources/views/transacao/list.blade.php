@extends('main')

@section('titulo', 'Gerenciar Transações - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Transações</h2>
    <div>
        <a href="{{ url('transacao/create') }}" class="btn btn-success">+ Nova Transação</a>
        <a href="{{ route('transacao.report') }}" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-1"></i> Relatório PDF</a>
        <a href="{{ route('transacao.report.categorias') }}" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-1"></i> Relatório Categorias</a>
        <a href="{{ route('transacao.chart') }}" class="btn btn-warning"><i class="fa-solid fa-chart-pie me-1"></i> Gráfico</a>
    </div>
</div>

<form action="{{ route('transacao.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="descricao_movimento">Descrição</option>
                <option value="nome_instituicao">Conta / Banco</option>
                <option value="nome_categoria">Categoria</option>
                <option value="metodo_pagamento">Método</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="valor" class="form-control" placeholder="Pesquisar transação...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i> Pesquisar</button>
            <a href="{{ url('transacao') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
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
                @php
                    $imagemComprovante = !empty($t->imagem) ? $t->imagem : 'sem_imagem.jpg';
                @endphp
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>
                        <img src="/storage/{{ $imagemComprovante }}" class="rounded shadow-sm" width="45" height="45" style="object-fit: cover;" alt="Comprovante">
                    </td>
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
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esta transação?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-3 text-muted">Nenhuma transação encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
