<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body { font-size: 12px; font-family: sans-serif; }
        .table th, .table td { padding: 6px; }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-12">
            <h3 class="mb-1">{{ $titulo }}</h3>
            <p class="text-muted">Emitido em: {{ $date }} | FinanSys - Gestão Financeira</p>
            <hr>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Título da Meta</th>
                        <th scope="col">Conta Bancária</th>
                        <th scope="col">Categoria</th>
                        <th scope="col" class="text-right">Valor Alvo</th>
                        <th scope="col" class="text-right">Valor Atual</th>
                        <th scope="col" class="text-center">Data Limite</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalAlvo = 0; 
                        $totalAtual = 0; 
                    @endphp
                    @forelse ($dados as $item)
                        @php 
                            $totalAlvo += (float) $item->valor_meta; 
                            $totalAtual += (float) $item->valor_atual; 
                        @endphp
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->titulo }}</td>
                            <td>{{ $item->conta->nome_instituicao ?? 'N/A' }}</td>
                            <td>{{ $item->categoria->nome_categoria ?? 'Sem Categoria' }}</td>
                            <td class="text-right">R$ {{ number_format($item->valor_meta, 2, ',', '.') }}</td>
                            <td class="text-right">R$ {{ number_format($item->valor_atual, 2, ',', '.') }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->data_limite)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma meta financeira cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-active font-weight-bold">
                        <td colspan="4" class="text-right">TOTAL:</td>
                        <td class="text-right">R$ {{ number_format($totalAlvo, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($totalAtual, 2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
