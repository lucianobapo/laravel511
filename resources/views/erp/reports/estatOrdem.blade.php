@extends('erp.app')
@section('contentWide')
    <h1 class="h1s">{{ trans('report.estatOrdem.title') }}</h1>
    <hr>
    {!! $viewTableTipoOrdem !!}

    <h2 class="h2s">{{ trans('report.estatOrdem.titleFinishedOrders') }}</h2>
    <hr>
    {!! $viewTableValoresMensais !!}

    <div class="panel panel-default">
        <div class="panel-heading"><h2 class="h2s">{{ trans('report.estatOrdem.panelOrdemVenda') }}</h2></div>
        <div class="panel-body">
            <h3 class="h3s">{{ trans('report.estatOrdem.tableOrdensPorMes.title') }}</h3>
            <hr>
            {!! $viewTableOrdensPorMes !!}

            <h3 class="h3s">{{ trans('report.estatOrdem.tableOrdensPorDia.title') }}</h3>
            <hr>
            {!! $viewTableOrdensPorDia !!}

            <h3 class="h3s">{{ trans('report.estatOrdem.tableOrdensPorSemana.title') }}</h3>
            <hr>
            {!! $viewTableOrdensPorSemana !!}

            <h3 class="h3s">{{ trans('report.estatOrdem.tableOrdensPorHora.title') }}</h3>
            <hr>
            {!! $viewTableOrdensPorHora !!}
        </div>
    </div>

@endsection
