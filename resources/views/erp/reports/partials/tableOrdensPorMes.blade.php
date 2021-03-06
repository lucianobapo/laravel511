<table class="table table-hover table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('report.estatOrdem.tableOrdensPorMes.diaMes') }}</th>
            @foreach($data as $key=>$value)
                <th>{{ $key }}</th>
            @endforeach
            <th>{{ trans('report.estatOrdem.tableOrdensPorMes.soma') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorMes.quantidade') }}</td>
            @foreach($data as $key=>$value)
                <td>{{ ($value) }}</td>
            @endforeach
            <td>{{ $soma }}</td>
        </tr>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorMes.valor') }}</td>
            @foreach($dataValor as $key=>$value)
                <td>{{ formatBRL($value) }}</td>
            @endforeach
            <td>{{ formatBRL($somaValor) }}</td>
        </tr>
    </tbody>
</table>