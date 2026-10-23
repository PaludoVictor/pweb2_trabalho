<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Metas Financeiras - FinanSys</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 flex flex-col justify-between">
    <div class="container px-4 py-8 mx-auto max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gráfico de Metas Financeiras (Formato Barras)</h1>
                <p class="text-sm text-gray-500">Comparativo entre o valor alvo estabelecido e o valor já economizado</p>
            </div>
            <a href="{{ url('meta') }}" class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded hover:bg-gray-700 transition">
                &larr; Voltar para Metas
            </a>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-md border border-gray-100">
            @if ($temDados)
                {!! $chart->container() !!}
            @else
                <p class="text-gray-600">Cadastre uma meta para visualizar o gráfico.</p>
            @endif
        </div>
    </div>



    @if ($temDados)
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}
    @endif
</body>
</html>

