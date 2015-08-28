<table class="table table-hover table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('report.estatOrdem.tableOrdensPorHora.hora') }}</th>
            @foreach($data as $key=>$value)
                @if($key<=12) <th>{{ $key }}</th> @endif
            @endforeach
            <th>{{ trans('report.estatOrdem.tableOrdensPorHora.soma') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorHora.quantidade') }}</td>
            <?php $soma15=0; ?>
            @foreach($data as $key=>$value)
                @if($key<=12 && ($soma15=$soma15+$value)) <td>{{ ($value) }}</td> @endif
            @endforeach
            <td>{{ $soma15 }}</td>
        </tr>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorHora.valor') }}</td>
            <?php $soma15=0; ?>
            @foreach($dataValor as $key=>$value)
                @if($key<=12 && ($soma15=$soma15+$value)) <td>{{ formatBRL($value) }}</td> @endif
            @endforeach
            <td>{{ formatBRL($soma15) }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-hover table-striped table-condensed">
    <thead>
    <tr>
        <th>{{ trans('report.estatOrdem.tableOrdensPorHora.hora') }}</th>
        @foreach($data as $key=>$value)
            @if($key>12) <th>{{ $key }}</th> @endif
        @endforeach
        <th>{{ trans('report.estatOrdem.tableOrdensPorHora.soma') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ trans('report.estatOrdem.tableOrdensPorHora.quantidade') }}</td>
        @foreach($data as $key=>$value)
            @if($key>12) <td>{{ ($value) }}</td> @endif
        @endforeach
        <td>{{ $soma }}</td>
    </tr>
    <tr>
        <td>{{ trans('report.estatOrdem.tableOrdensPorHora.valor') }}</td>
        @foreach($dataValor as $key=>$value)
            @if($key>12) <td>{{ formatBRL($value) }}</td> @endif
        @endforeach
        <td>{{ formatBRL($somaValor) }}</td>
    </tr>
    </tbody>
</table>