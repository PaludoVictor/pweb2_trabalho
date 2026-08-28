@extends('main')

@section('titulo', 'Resumo Financeiro - FinanSys')

@section('conteudo')
<div class="d-flex justify-content-between align-items-end mb-5">
    <div>
        <h2 class="fw-bold mb-1" style="color: #111827;">Resumo Financeiro</h2>
        <p class="text-muted mb-0">Acompanhamento em tempo real dos seus lançamentos.</p>
    </div>
    <a href="{{ url('transacao/create') }}" class="btn btn-success px-4 py-2" style="background-color: #10b981; border: none;">
        <i class="fa-solid fa-plus me-2"></i>Nova Transação
    </a>
</div>

@if ($dadosGrafico->isEmpty() || $totalTransacoes == 0)
    <div class="card card-modern p-5 text-center shadow-sm">
        <div class="py-5">
            <i class="fa-solid fa-file-invoice text-muted opacity-25 fa-4x mb-3"></i>
            <h5 class="fw-bold text-dark">Nenhuma transação registrada</h5>
            <p class="text-muted">Cadastre sua primeira transação para visualizar o resumo e o gráfico.</p>
            <a href="{{ url('transacao/create') }}" class="btn btn-success mt-3" style="background-color: #10b981; border: none;">
                Começar agora
            </a>
        </div>
    </div>
@else
    <div class="row">
        <!-- Gráfico de Gastos por Categoria usando Bootstrap -->
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card card-modern shadow-sm border-0">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4 text-dark text-center">Distribuição de Lançamentos por Categoria</h4>
                    
                    <!-- Barra de Progresso Empilhada (Stacked Progress Bar) -->
                    <div class="progress mb-4" style="height: 30px; border-radius: 8px;">
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

                    <!-- Detalhamento por Categoria com barras individuais -->
                    <div class="mt-4">
                        @foreach ($dadosGrafico as $d)
                            @php
                                $pct = $totalTransacoes > 0 ? ($d['total'] / $totalTransacoes) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-medium">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 12px; height: 12px; background-color: {{ $d['cor_identificacao'] }};"></span>
                                        {{ $d['nome_categoria'] }}
                                    </span>
                                    <span class="text-muted small">
                                        R$ {{ number_format($d['total'], 2, ',', '.') }} 
                                        <span class="badge bg-light text-dark ms-2">{{ number_format($pct, 1, ',', '.') }}%</span>
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 4px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $pct }}%; background-color: {{ $d['cor_identificacao'] }};" 
                                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Geral -->
                    <div class="text-center mt-5 pt-3 border-top text-muted">
                        <span class="fs-5">Total Geral: <strong class="text-dark">R$ {{ number_format($totalTransacoes, 2, ',', '.') }}</strong></span>
                    </div>
                </div>
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
@endif
@stop
