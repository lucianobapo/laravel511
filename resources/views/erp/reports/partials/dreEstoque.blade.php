<tr class="h5">
    <td>{{ trans('report.dre.estoque.subtotal') }}</td>
    @if(!$acumulado=0)
        @foreach($periodos as $periodo)
            <td>{{ formatBRL($acumulado=$acumulado+$periodo['ordersMes']['saldo']) }}</td>
        @endforeach
    @endif
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.estoque.compras') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['comprasEstoque']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('report.dre.estoque.comprasMercadorias') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['comprasMercadorias']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('report.dre.estoque.comprasLanches') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['comprasLanches']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.estoque.consumo') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['custoMedioVendas']) }}</td>
    @endforeach
</tr>
<tr>
    <td>&nbsp;&nbsp;&nbsp;{{ trans('report.dre.estoque.saldo') }}</td>
    @foreach($periodos as $periodo)
        <td>{{ formatBRL($periodo['ordersMes']['saldo']) }}</td>
    @endforeach
</tr>