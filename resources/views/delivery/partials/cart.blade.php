<table class="table" id="cartTable">
    {{--<thead>--}}
        {{--<tr>--}}
            {{--<th></th>--}}
        {{--</tr>--}}
    {{--</thead>--}}
    <tbody>
        @foreach($cart as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['qty'] }}</td>
                <td>{{ formatBRL($row['price']) }}</td>
                <td>{{ formatBRL($row['subtotal']) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="">
    {{ trans('delivery.nav.cartTotal') }}: {{ formatBRL(Cart::total()) }}
</div>
<div class="">
    {!! isset($host)? link_to_route('delivery.pedido', trans('delivery.nav.cartBtn'), $host, ['class'=>'btn btn-success']):false !!}
</div>