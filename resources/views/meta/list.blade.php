@extends('main')

@section('titulo', 'Metas Financeiras - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Metas Financeiras</h2>
    <a href="{{ url('meta/create') }}" class="btn btn-primary">+ Nova Meta</a>
</div>

<form action="{{ route('meta.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-9">
            <input type="text" name="valor" class="form-control" placeholder="Buscar meta por título...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
            <a href="{{ url('meta') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
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
                @php
                    $nome_imagem = !empty($item->imagem) ? $item->imagem : 'sem_imagem.jpg';
                @endphp
                <tr>
                    <td>
                        <img src="/storage/{{ $nome_imagem }}" width="40" height="40" style="object-fit: cover; border-radius: 4px;" alt="Foto">
                    </td>
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
                    <td colspan="8" class="text-center py-3 text-muted">Nenhuma meta financeira cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
