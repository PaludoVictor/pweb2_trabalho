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
            <div class="card-body p-4">
                {!! $graficoGastos->container() !!}
            </div>
        </div>
    </div>

    <!-- Metade 2: Gráfico de Metas Financeiras -->
    <div class="col-md-6 mb-4">
        <div class="card card-modern shadow-sm border-0 h-100">
            <div class="card-body p-4">
                {!! $graficoMetas->container() !!}
            </div>
        </div>
    </div>
</div>

<script src="{{ $graficoGastos->cdn() }}"></script>
{{ $graficoGastos->script() }}
{{ $graficoMetas->script() }}
@stop
