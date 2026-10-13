<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body { font-size: 12px; font-family: sans-serif; }
        .table th, .table td { padding: 5px; }
        .categoria-header { background-color: #f8f9fa; border-left: 4px solid #10b981; padding: 6px 12px; margin-top: 15px; margin-bottom: 8px; }
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

    <div class="row">
        <div class="col-12">
            @foreach ($dados as $cat)
                <div class="categoria-header">
                    <strong>Categoria: {{ $cat->nome_categoria }}</strong> 
                    <span class="text-muted">({{ $cat->tipo_despesa }} - Limite Orçado: R$ {{ number_format($cat->limite_orcamento, 2, ',', '.') }})</span>
                </div>

                @if ($cat->transacoes->isEmpty())
                    <p class="text-muted font-italic pl-3">Nenhuma movimentação registrada nesta categoria.</p>
                @else
                    <p class="mb-1 pl-1">Total de Movimentações: <strong>{{ $cat->transacoes->count() }}</strong></p>
                    <table class="table table-sm table-striped table-bordered mb-4">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Data</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Conta Bancária</th>
                                <th scope="col">Método</th>
                                <th scope="col" class="text-right">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach ($cat->transacoes as $t)
                                @php $subtotal += (float) $t->valor_transacao; @endphp
                                <tr>
                                    <th scope="row">{{ $t->id }}</th>
                                    <td>{{ date('d/m/Y', strtotime($t->data_competencia)) }}</td>
                                    <td>{{ $t->descricao_movimento }}</td>
                                    <td>{{ $t->conta->nome_instituicao ?? 'N/A' }}</td>
                                    <td>{{ $t->metodo_pagamento }}</td>
                                    <td class="text-right">R$ {{ number_format($t->valor_transacao, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary font-weight-bold">
                                <td colspan="5" class="text-right">Subtotal da Categoria:</td>
                                <td class="text-right">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            @endforeach
        </div>
    </div>
</body>
</html>

