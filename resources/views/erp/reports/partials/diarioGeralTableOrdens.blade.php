<table class="table table-hover table-striped table-condensed" ng-app="myApp">
    <thead>
    <tr>
        <th>{{ trans('report.diarioGeral.data') }}</th>
        <th>{{ trans('report.diarioGeral.contaDebitada') }}</th>
        <th>{{ trans('report.diarioGeral.contaCreditada') }}</th>
        <th>{{ trans('report.diarioGeral.valor') }}</th>
        <th>{{ trans('report.diarioGeral.transacao') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($diario as $key => $day)
        @foreach($day['items'] as $id => $item)
            <tr>
                <td>{{ $day['posted_at'] }}</td>
                <td>{{ $item['debito']->numero.' '.$item['debito']->descricao }}</td>
                <td>{{ $item['credito']->numero.' '.$item['credito']->descricao }}</td>
                <td>{{ formatBRL($item['valor']) }}</td>
                <td>{{ $day['transacao'].': '.$day['order']->type->descricao.' - '.$item['descricao'] }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>