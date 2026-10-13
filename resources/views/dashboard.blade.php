@extends('main')

@section('titulo', 'Resumo Financeiro - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #111827;">Painel de Controle Financeiro</h2>
        <p class="text-muted mb-0">Visão consolidada das suas finanças e lançamentos em tempo real.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transacao.report') }}" class="btn btn-outline-danger">
            <i class="fa-solid fa-file-pdf me-1"></i> Relatório PDF
        </a>
        <a href="{{ route('transacao.chart') }}" class="btn btn-outline-warning">
            <i class="fa-solid fa-chart-pie me-1"></i> Gráfico de Gastos
        </a>
        <a href="{{ url('transacao/create') }}" class="btn btn-success" style="background-color: #10b981; border: none;">
            <i class="fa-solid fa-plus me-1"></i> Nova Transação
        </a>
    </div>
</div>

{{-- KPI Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-modern shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fa-solid fa-building-columns fa-xl"></i>
                </div>
                <div>
                    <span class="text-muted small">Total em Contas</span>
                    <h5 class="fw-bold mb-0 text-dark">R$ {{ number_format($totalContas, 2, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-danger bg-opacity-10 text-danger me-3">
                    <i class="fa-solid fa-arrow-trend-down fa-xl"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Lançado</span>
                    <h5 class="fw-bold mb-0 text-dark">R$ {{ number_format($totalTransacoes, 2, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info me-3">
                    <i class="fa-solid fa-layer-group fa-xl"></i>
                </div>
                <div>
                    <span class="text-muted small">Limite Orçamento</span>
                    <h5 class="fw-bold mb-0 text-dark">R$ {{ number_format($totalOrcamento, 2, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-modern shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success me-3">
                    <i class="fa-solid fa-piggy-bank fa-xl"></i>
                </div>
                <div>
                    <span class="text-muted small">Saldo Orçamentário</span>
                    <h5 class="fw-bold mb-0 text-dark">R$ {{ number_format($saldoRestante, 2, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Coluna da Distribuição por Categoria -->
    <div class="col-lg-7">
        <div class="card card-modern shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-dark">Distribuição por Categoria</h5>
                
                @if ($dadosGrafico->isEmpty() || $totalTransacoes == 0)
                    <div class="text-center py-4 text-muted">
                        <i class="fa-solid fa-chart-pie fa-2x mb-2 opacity-50"></i>
                        <p class="mb-0">Nenhuma transação registrada para exibir a distribuição.</p>
                    </div>
                @else
                    <!-- Barra de Progresso Empilhada -->
                    <div class="progress mb-4" style="height: 24px; border-radius: 8px;">
                        @foreach ($dadosGrafico as $d)
                            @php
                                $pct = $totalTransacoes > 0 ? ($d['total'] / $totalTransacoes) * 100 : 0;
                            @endphp
                            @if ($pct > 0)
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $pct }}%; background-color: {{ $d['cor_identificacao'] }};" 
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                                     data-bs-toggle="tooltip" 
                                     title="{{ $d['nome_categoria'] }}: {{ number_format($pct, 1, ',', '.') }}%">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Detalhamento por Categoria -->
                    @foreach ($dadosGrafico as $d)
                        @php
                            $pct = $totalTransacoes > 0 ? ($d['total'] / $totalTransacoes) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium small">
                                    <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background-color: {{ $d['cor_identificacao'] }};"></span>
                                    {{ $d['nome_categoria'] }}
                                </span>
                                <span class="text-muted small">
                                    R$ {{ number_format($d['total'], 2, ',', '.') }} 
                                    <span class="badge bg-light text-dark ms-1">{{ number_format($pct, 1, ',', '.') }}%</span>
                                </span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 3px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $pct }}%; background-color: {{ $d['cor_identificacao'] }};" 
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Coluna de Acesso Rápido e Módulos -->
    <div class="col-lg-5">
        <div class="card card-modern shadow-sm border-0 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3 text-dark">Módulos do Sistema</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ url('meta') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <div>
                                <i class="fa-solid fa-bullseye text-success me-2"></i>
                                <strong>Metas Financeiras</strong>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Novo Módulo</span>
                        </a>
                        <a href="{{ url('cartao') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <div>
                                <i class="fa-solid fa-credit-card text-primary me-2"></i>
                                <strong>Cartões de Crédito</strong>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Novo Módulo</span>
                        </a>
                        <a href="{{ url('transacao') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <div>
                                <i class="fa-solid fa-arrow-right-arrow-left text-warning me-2"></i>
                                <strong>Transações com Comprovante</strong>
                            </div>
                            <span class="badge bg-secondary rounded-pill">Upload</span>
                        </a>
                        <a href="{{ url('conta') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                            <div>
                                <i class="fa-solid fa-building-columns text-info me-2"></i>
                                <strong>Contas Bancárias</strong>
                            </div>
                            <span class="badge bg-secondary rounded-pill">Gestão</span>
                        </a>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded bg-light border">
                    <h6 class="fw-bold mb-1 text-dark"><i class="fa-solid fa-circle-info text-primary me-1"></i> Relatórios e Gráficos</h6>
                    <p class="small text-muted mb-2">Acesse relatórios gerados em PDF e gráficos em múltiplos formatos.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('transacao.report') }}" class="btn btn-sm btn-danger"><i class="fa-solid fa-file-pdf"></i> PDF Geral</a>
                        <a href="{{ route('transacao.report.categorias') }}" class="btn btn-sm btn-danger"><i class="fa-solid fa-file-pdf"></i> PDF Categorias</a>
                        <a href="{{ route('meta.chart') }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-chart-column"></i> Gráfico Barras</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Transações Registradas -->
<div class="card card-modern shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-dark">Últimas Transações</h5>
            <a href="{{ url('transacao') }}" class="btn btn-sm btn-outline-secondary">Ver todas &rarr;</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Conta</th>
                        <th>Categoria</th>
                        <th>Método</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimasTransacoes as $ut)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($ut->data_competencia)) }}</td>
                        <td class="fw-semibold">{{ $ut->descricao_movimento }}</td>
                        <td>{{ $ut->conta->nome_instituicao ?? 'N/A' }}</td>
                        <td>
                            @if (isset($ut->categoria))
                                <span class="badge" style="background-color: {{ $ut->categoria->cor_identificacao }};">
                                    {{ $ut->categoria->nome_categoria }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Sem Categoria</span>
                            @endif
                        </td>
                        <td>{{ $ut->metodo_pagamento }}</td>
                        <td class="fw-bold text-dark">R$ {{ number_format($ut->valor_transacao, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3 text-muted">Nenhuma transação recente encontrada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ativação dos Tooltips do Bootstrap -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@stop
