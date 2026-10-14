@extends('main')

@section('titulo', 'Dashboard - FinanSys')

@section('conteudo')
<div class="mb-4">
    <h2 class="fw-bold mb-1" style="color: #111827;">Dashboard Financeiro</h2>
    <p class="text-muted mb-0">Acompanhamento dos gastos por categoria e progresso das metas financeiras.</p>
</div>

<div class="row">
    <!-- Metade 1: Gráfico Pizza de Gastos por Categoria -->
    <div class="col-md-6 mb-4">
        <div class="card card-modern shadow-sm border-0 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Gastos por Categoria</h5>
                        <a href="{{ route('transacao.report.categorias') }}" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-file-pdf me-1"></i> Baixar Relatório PDF
                        </a>
                    </div>
                    {!! $graficoGastos->container() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Metade 2: Gráfico de Metas Financeiras -->
    <div class="col-md-6 mb-4">
        <div class="card card-modern shadow-sm border-0 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Metas Financeiras</h5>
                        <a href="{{ route('meta.report') }}" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-file-pdf me-1"></i> Baixar Relatório PDF
                        </a>
                    </div>
                    {!! $graficoMetas->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ $graficoGastos->cdn() }}"></script>
{{ $graficoGastos->script() }}
{{ $graficoMetas->script() }}
@stop
