<tr class="h5">
    <td>{{ trans('report.dre.custo.title') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['custoProdutos']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.custo.consumo') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['consumoMedioEstoque']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.custo.custoMercadorias') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['comprasMercadorias']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.custo.custoLanches') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['comprasLanches']) }}</td>
    @endforeach
</tr>