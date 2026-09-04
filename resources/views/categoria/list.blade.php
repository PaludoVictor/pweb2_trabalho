@extends('main')

@section('titulo', 'Gerenciar Categorias - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Categorias</h2>
    <a href="{{ url('categoria/create') }}" class="btn btn-primary">+ Nova Categoria</a>
</div>

<form action="{{ route('categoria.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-8">
            <input type="text" name="valor" class="form-control" placeholder="Buscar categoria por nome...">
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
                    <th>Cor</th>
                    <th>Nome da Categoria</th>
                    <th>Tipo</th>
                    <th>Orçamento Limite</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $c)
                <tr>
                    <td>
                        <div style="width: 25px; height: 25px; border-radius: 50%; background-color: {{ $c->cor_identificacao }};"></div>
                    </td>
                    <td>{{ $c->nome_categoria }}</td>
                    <td>{{ $c->tipo_despesa }}</td>
                    <td>R$ {{ number_format($c->limite_orcamento, 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('categoria.edit', $c->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('categoria.destroy', $c->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-3 text-muted">Nenhuma categoria cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
