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
    </ul>
    <hr class="border-secondary">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                <i class="fa-solid fa-user text-white small"></i>
            </div>
            <strong>{{ session('usuario_nome', 'Admin') }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="{{ url('usuario') }}">Gerenciar Usuários</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger fw-bold" href="{{ route('logout') }}">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                </a>
            </li>
        </ul>
    </div>
</div>
