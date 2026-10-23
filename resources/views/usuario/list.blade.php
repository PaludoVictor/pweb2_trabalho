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
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="nome">Nome</option>
                <option value="cpf">CPF</option>
                <option value="email">E-mail</option>
                <option value="login">Login</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="valor" class="form-control" placeholder="Pesquisar usuário...">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" type="submit">Pesquisar</button>
            <a href="{{ url('usuario') }}" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Login</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dados as $u)
                @php
                    $url_imagem = !empty($u->imagem) ? asset('storage/' . $u->imagem) : asset('images/sem_imagem.svg');
                @endphp
                <tr>
                    <td>
                        <img src="{{ $url_imagem }}" width="42" height="42" class="rounded-circle shadow-sm" style="object-fit: cover; border: 2px solid #e5e7eb;" alt="Foto">
                    </td>
                    <td><strong>{{ $u->nome }}</strong></td>
                    <td>{{ $u->cpf ?? '-' }}</td>
                    <td>{{ $u->telefone }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge bg-secondary">{{ $u->login }}</span></td>
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
                    <td colspan="7" class="text-center py-3 text-muted">Nenhum usuário cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop
