@extends('main')

@section('titulo', 'Gerenciar Usuários - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciar Usuários</h2>
    <a href="{{ url('usuario/create') }}" class="btn btn-primary">+ Novo Usuário</a>
</div>

<form action="{{ route('usuario.search') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-2 align-items-center">
        <div class="col-md-8">
            <input type="text" name="valor" class="form-control" placeholder="Buscar usuário por nome...">
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
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Login</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->nome }}</td>
                    <td>{{ $u->telefone }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->login }}</td>
                    <td>
                        <a href="{{ route('usuario.edit', $u->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('usuario.destroy', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">Nenhum usuário cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
