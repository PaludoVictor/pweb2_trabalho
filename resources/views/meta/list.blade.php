@extends('main')

@section('titulo', 'Metas Financeiras - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Metas Financeiras</h2>
    <div>
        <a href="{{ url('meta/create') }}" class="btn btn-success">+ Nova Meta</a>
        <a href="{{ route('meta.chart') }}" class="btn btn-warning"><i class="fa-solid fa-chart-column me-1"></i> Gráfico de Metas</a>
    </div>
</div>

<form action="{{ route('meta.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-9">
            <input type="text" name="valor" class="form-control" placeholder="Pesquisar por título da meta...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i> Pesquisar</button>
            <a href="{{ url('meta') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto/Ícone</th>
                    <th>Título</th>
                    <th>Conta</th>
                    <th>Categoria</th>
                    <th>Valor Alvo</th>
                    <th>Valor Atual</th>
                    <th>Progresso</th>
                    <th>Data Limite</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $item)
                @php
                    $nome_imagem = !empty($item->imagem) ? $item->imagem : 'sem_imagem.jpg';
                    $pct = $item->valor_meta > 0 ? min(100, round(($item->valor_atual / $item->valor_meta) * 100)) : 0;
                @endphp
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        <img src="/storage/{{ $nome_imagem }}" class="rounded shadow-sm" width="45" height="45" style="object-fit: cover;" alt="Meta">
                    </td>
                    <td class="fw-semibold">{{ $item->titulo }}</td>
                    <td>{{ $item->conta->nome_instituicao ?? 'N/A' }}</td>
                    <td>
                        @if (isset($item->categoria))
                            <span class="badge" style="background-color: {{ $item->categoria->cor_identificacao }};">
                                {{ $item->categoria->nome_categoria }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Sem Categoria</span>
                        @endif
                    </td>
                    <td>R$ {{ number_format($item->valor_meta, 2, ',', '.') }}</td>
                    <td class="text-success fw-bold">R$ {{ number_format($item->valor_atual, 2, ',', '.') }}</td>
                    <td style="min-width: 110px;">
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="small text-muted">{{ $pct }}%</span>
                        </div>
                    </td>
                    <td>{{ date('d/m/Y', strtotime($item->data_limite)) }}</td>
                    <td>
                        <a href="{{ route('meta.edit', $item->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('meta.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir esta meta?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-3 text-muted">Nenhuma meta financeira cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
