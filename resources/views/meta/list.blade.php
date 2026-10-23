@extends('main')

@section('titulo', 'Metas Financeiras - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Metas Financeiras</h2>
    <div>
        <a href="{{ url('meta/create') }}" class="btn btn-success">Nova Meta</a>
        <a href="{{ route('meta.chart') }}" class="btn btn-warning">Gráfico</a>
    </div>
</div>

<form action="{{ route('meta.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-lg-3">
            <input type="text" name="valor" class="form-control" value="{{ request('valor') }}" placeholder="Buscar meta por título...">
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
            <a href="{{ url('meta') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Conta</th>
                    <th>Categoria</th>
                    <th>Valor Alvo</th>
                    <th>Valor Atual</th>
                    <th>Data Limite</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $item)
                <tr>
                    <td>{{ $item->titulo }}</td>
                    <td>{{ $item->conta->nome_instituicao ?? 'N/A' }}</td>
                    <td>{{ $item->categoria->nome_categoria ?? 'Sem Categoria' }}</td>
                    <td>R$ {{ number_format($item->valor_meta, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->valor_atual, 2, ',', '.') }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->data_limite)) }}</td>
                    <td>
                        <a href="{{ route('meta.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('meta.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">Nenhuma meta financeira cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop
