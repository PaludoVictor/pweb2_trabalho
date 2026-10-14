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
                        <th scope="col">Instituição Financeira</th>
                        <th scope="col">Agência</th>
                        <th scope="col">Número da Conta</th>
                        <th scope="col" class="text-right">Saldo Atual</th>
                    </tr>
                </thead>
                <tbody>
                    @php $saldoTotal = 0; @endphp
                    @forelse ($dados as $item)
                        @php $saldoTotal += (float) $item->saldo_atual; @endphp
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->nome_instituicao }}</td>
                            <td>{{ $item->agencia_numero }}</td>
                            <td>{{ $item->numero_conta }}</td>
                            <td class="text-right">R$ {{ number_format($item->saldo_atual, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma conta bancária cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-active font-weight-bold">
                        <td colspan="4" class="text-right">SALDO TOTAL CONSOLIDADO:</td>
                        <td class="text-right">R$ {{ number_format($saldoTotal, 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>

