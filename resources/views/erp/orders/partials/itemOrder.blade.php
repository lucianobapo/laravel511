<table class="table table-hover table-condensed table-bordered">
    <thead>
        <tr>
            <th>{{ trans('modelProduct.attributes.nome') }}</th>
            <th>{{ trans('modelItemOrder.attributes.cost_id') }}</th>
            <th>{{ trans('modelItemOrder.attributes.quantidade') }}</th>
            <th>{{ trans('modelItemOrder.attributes.valor_unitario') }}</th>
            <th>{{ trans('modelItemOrder.attributes.currency') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->nome }}</td>
                <td>{{ $item->cost->cost }}</td>
                <td>{{ $item->quantidade }}</td>
                <td>{{ formatBRL($item->valor_unitario) }}</td>
                <td>{{ $item->currency->nome_universal   }}</td>
            </tr>
        @endforeach
    </tbody>
</table>