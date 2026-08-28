@extends('main')

@section('titulo', 'Gerenciar Contas Bancárias - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Contas Bancárias</h2>
    <a href="{{ url('conta/create') }}" class="btn btn-primary">+ Nova Conta</a>
</div>

<form action="{{ route('conta.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="nome_instituicao">Instituição</option>
                <option value="numero_conta">Número da Conta</option>
                <option value="agencia_numero">Agência</option>
            </select>
        </div>
        <div class="col-md-5">
            <input type="text" name="valor" class="form-control" placeholder="Buscar por instituição ou conta...">
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
                    <th>ID</th>
                    <th>Instituição</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th>Saldo Atual</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->nome_instituicao }}</td>
                    <td>{{ $c->agencia_numero }}</td>
                    <td>{{ $c->numero_conta }}</td>
                    <td class="{{ $c->saldo_atual >= 0 ? 'text-success' : 'text-danger'; }} fw-bold">
                        R$ {{ number_format($c->saldo_atual, 2, ',', '.') }}
                    </td>
                    <td>
                        <a href="{{ route('conta.edit', $c->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('conta.destroy', $c->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">Nenhuma conta bancária cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
