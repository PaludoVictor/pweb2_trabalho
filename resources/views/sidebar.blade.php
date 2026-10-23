<div class="sidebar d-flex flex-column flex-shrink-0 p-3 vh-100 sticky-top">
    <a href="{{ url('/') }}" class="sidebar-brand d-flex align-items-center mb-4 px-2">
        <i class="fa-solid fa-money-bills me-2"></i> FinanSys
    </a>
    <hr class="border-secondary mt-0">
    <ul class="nav nav-pills flex-column mb-auto nav-sidebar">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active-link' : '' }}">
                <i class="fa-solid fa-border-all me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ url('conta') }}" class="nav-link {{ Request::is('conta*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-building-columns me-2"></i> Contas Bancárias
            </a>
        </li>
        <li>
            <a href="{{ url('categoria') }}" class="nav-link {{ Request::is('categoria*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-layer-group me-2"></i> Categorias
            </a>
        </li>
        <li>
            <a href="{{ url('transacao') }}" class="nav-link {{ Request::is('transacao*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-arrow-right-arrow-left me-2"></i> Transações
            </a>
        </li>
        <li>
            <a href="{{ url('meta') }}" class="nav-link {{ Request::is('meta*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-bullseye me-2"></i> Metas Financeiras
            </a>
        </li>
        <li>
            <a href="{{ url('cartao') }}" class="nav-link {{ Request::is('cartao*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-credit-card me-2"></i> Cartões de Crédito
            </a>
        </li>
        <li>
            <a href="{{ url('usuario') }}" class="nav-link {{ Request::is('usuario*') ? 'active-link' : '' }}">
                <i class="fa-solid fa-users me-2"></i> Usuários
            </a>
        </li>
    </ul>
    <hr class="border-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2 overflow-hidden" style="width: 32px; height: 32px;">
                @if (session('usuario_imagem'))
                    <img src="/storage/{{ session('usuario_imagem') }}" width="32" height="32" style="object-fit: cover;" alt="Foto">
                @else
                    <i class="fa-solid fa-user text-white small"></i>
                @endif
            </div>
            <strong>{{ session('usuario_nome', 'Admin') }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="{{ url('usuario') }}">Gerenciar Usuários</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger fw-bold">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
