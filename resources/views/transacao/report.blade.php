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
        .badge-info { background-color: #17a2b8; color: #fff; padding: 3px 6px; border-radius: 3px; }
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
                        <th scope="col">Data</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Conta Bancária</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Método</th>
                        <th scope="col" class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalGeral = 0; @endphp
                    @forelse ($dados as $item)
                        @php $totalGeral += (float) $item->valor_transacao; @endphp
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ date('d/m/Y', strtotime($item->data_competencia)) }}</td>
                            <td>{{ $item->descricao_movimento }}</td>
                            <td>{{ $item->conta->nome_instituicao ?? 'N/A' }}</td>
                            <td>{{ $item->categoria->nome_categoria ?? 'Sem Categoria' }}</td>
                            <td>{{ $item->metodo_pagamento }}</td>
                            <td class="text-right">R$ {{ number_format($item->valor_transacao, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma transação encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-active font-weight-bold">
                        <td colspan="6" class="text-right">TOTAL GERAL:</td>
                        <td class="text-right">R$ {{ number_format($totalGeral, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>

