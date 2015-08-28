<table class="table table-hover table-striped table-condensed">
    <thead>
        <tr>
            <th>{{ trans('report.estatOrdem.tableOrdensPorSemana.semana') }}</th>
            @foreach($data as $key=>$value)
                <th>{{ $key }}</th>
            @endforeach
            <th>{{ trans('report.estatOrdem.tableOrdensPorSemana.soma') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorSemana.quantidade') }}</td>
            @foreach($data as $key=>$value)
                <td>
                    {{ ($value) }}
                    <strong class="small" style="color: #0000ff;">{{ isset($dataPosicao[$key])?$dataPosicao[$key].'º':'' }}</strong>
                </td>
            @endforeach
            <td>{{ $soma }}</td>
        </tr>
        <tr>
            <td>{{ trans('report.estatOrdem.tableOrdensPorSemana.valor') }}</td>
            @foreach($dataValor as $key=>$value)
                <td>
                    {{ formatBRL($value) }}
                    <strong class="small" style="color: #0000ff;">{{ isset($dataValorPosicao[$key])?$dataValorPosicao[$key].'º':'' }}</strong>
                </td>
            @endforeach
            <td>{{ formatBRL($somaValor) }}</td>
        </tr>
    </tbody>
</table>