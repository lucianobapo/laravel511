<tr class="h5">
    <td>{{ trans('report.dre.despesas') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesas']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesasGerais') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasGerais']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesasMensaisFixas') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasMensaisFixas']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesasTransporte') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasTransporte']) }}</td>
    @endforeach
</tr>