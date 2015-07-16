<tr class="h5">
    <td>{{ trans('report.dre.despesas.title') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesas']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesas.despesasGerais') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasGerais']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesas.despesasMensaisFixas') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasMensaisFixas']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesas.despesasMarketingPropaganda') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasMarketingPropaganda']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.despesas.despesasTransporte') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['despesasTransporte']) }}</td>
    @endforeach
</tr>