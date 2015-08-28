<table class="table table-hover table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('report.estatOrdem.tableOrdensPorDia.diaMes') }}</th>
            @foreach($data as $key=>$value)
                @if($key<=15) <th>{{ $key }}</th> @endif
            @endforeach
            <th>{{ trans('report.estatOrdem.tableOrdensPorDia.soma') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorDia.quantidade') }}</td>
            <?php $soma15=0; ?>
            @foreach($data as $key=>$value)
                @if($key<=15 && ($soma15=$soma15+$value)) <td>{{ ($value) }}</td> @endif
            @endforeach
            <td>{{ $soma15 }}</td>
        </tr>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorDia.valor') }}</td>
            <?php $soma15=0; ?>
            @foreach($dataValor as $key=>$value)
                @if($key<=15 && ($soma15=$soma15+$value)) <td>{{ formatBRL($value) }}</td> @endif
            @endforeach
            <td>{{ formatBRL($soma15) }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-hover table-striped table-condensed">
    <thead>
    <tr>
        <th>{{ trans('report.estatOrdem.tableOrdensPorDia.diaMes') }}</th>
        @foreach($data as $key=>$value)
            @if($key>15) <th>{{ $key }}</th> @endif
        @endforeach
        <th>{{ trans('report.estatOrdem.tableOrdensPorDia.soma') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ trans('report.estatOrdem.tableOrdensPorDia.quantidade') }}</td>
        @foreach($data as $key=>$value)
            @if($key>15) <td>{{ ($value) }}</td> @endif
        @endforeach
        <td>{{ $soma }}</td>
    </tr>
    <tr>
        <td>{{ trans('report.estatOrdem.tableOrdensPorDia.valor') }}</td>
        @foreach($dataValor as $key=>$value)
            @if($key>15) <td>{{ formatBRL($value) }}</td> @endif
        @endforeach
        <td>{{ formatBRL($somaValor) }}</td>
    </tr>
    </tbody>
</table>