<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'FinanSys - Gestão Financeira')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f3f4f6; /* Fundo cinza bem claro para o conteúdo */
        }
        
        /* Estilização da Sidebar (Barra Lateral) */
        .sidebar {
            width: 260px;
            background-color: #111827; /* Cinza chumbo escuro e moderno */
            color: #9ca3af;
            border-right: 1px solid #1f2937;
        }
        .sidebar-brand {
            color: #10b981; /* Verde esmeralda */
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-decoration: none;
        }
        .nav-sidebar .nav-link {
            color: #9ca3af;
            border-radius: 8px;
            margin-bottom: 5px;
            padding: 10px 15px;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }
        .nav-sidebar .nav-link:hover {
            background-color: #1f2937;
            color: #10b981;
        }
        .nav-sidebar .nav-link.active-link,
        .nav-sidebar .nav-link.active {
            background-color: #10b981;
            color: #ffffff;
        }
        
        /* Área principal de conteúdo */
        .main-content {
            min-height: 100vh;
        }
        
        /* Card customizado para o painel */
        .card-modern {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            background: #ffffff;
        }
    </style>
</head>
<body>

<div class="d-flex flex-nowrap">
    @include('sidebar')

    <main class="main-content flex-grow-1 d-flex flex-column">
        <div class="container-fluid p-5 flex-grow-1">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="fechar"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <b>Por favor, verifique os erros abaixo:</b>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="fechar"></button>
                </div>
            @endif

            @yield('conteudo')
        </div>

        <footer class="text-center py-3 mt-auto border-top" style="background-color: #ffffff;">
            <p class="mb-0 small text-muted">
                &copy; {{ date('Y') }} <strong>FinanSys</strong>. Desenvolvido para PWEB 2
            </p>
        </footer>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
