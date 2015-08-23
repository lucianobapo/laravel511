<h1 class="h1s">{{ trans('report.cardapio.title') }}</h1>
<h3>{{ trans('report.cardapio.reportTime',['tempo'=>Carbon\Carbon::now()->format('d/m/Y H:i')]) }}</h3>
<hr>
<table style="" class="table table-hover table-striped table-condensed" ng-app="myApp">
    <thead style="">
    <tr>
        <th style="border: 0px"></th>
        <th style="border: 0px"></th>
        <th style="border: 0px"></th>
    </tr>
    <tr>
        <th style="border: 0px;">{{ trans('report.cardapio.id') }}</th>
        <th style="border: 0px;">{{ trans('report.cardapio.produto') }}</th>
        <th style="border: 0px;">{{ trans('report.cardapio.categoria') }}</th>
        <th style="border: 0px;">{{ trans('report.cardapio.valorVenda') }}</th>
    </tr>
    </thead>
    <tbody style="">
    <tr style="background-color: #ffffff;">
        <td style="border: 0px;"></td>
        <td style="border: 0px;"></td>
        <td style="border: 0px;"></td>
    </tr>
        <tr ng-repeat="p in products">
            <td>@{{ p.id }}</td>
            <td>@{{ p.nome }}</td>
            <td>@{{ p.categoria_list }}</td>
            <td>@{{ p.valorUnitVenda }}</td>
        </tr>
    </tbody>
</table>