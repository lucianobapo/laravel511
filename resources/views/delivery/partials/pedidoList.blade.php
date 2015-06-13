<div class="" style="list-style-type: none; padding: 0px;">
    @foreach($cart as $row)
        @if($product = $product->where(['id' => $row['id']])->first())
            <div class="col-sm-2 well" style="min-width: 200px;
                float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 5px 5px;">
                @include('delivery.partials.productBlock')
                <div style="padding: 5px;">
                    <strong>
                        {{ $row['qty'] }}x -> {{ formatBRL($row['subtotal']) }}
                    </strong>
                </div>
            </div>
        @endif
    @endforeach
    <div>
        <h3>{!! trans('delivery.pedidos.valorTotal').': <strong>'.$totalCart.'</strong>' !!}</h3>
    </div>

</div>
<div class="row">
    <div class="col-sm-3" style="margin: 20px 0px">
        {!! link_to_route('delivery.emptyCart', trans('delivery.pedidos.emptyBtn'), $host, [
        'class' => 'btn btn-warning form-control',
        'style' => ''
        ]) !!}
    </div>
    <div class="col-sm-3" style="margin: 20px 0px">
        {!! link_to_route('delivery.index', trans('delivery.pedidos.continueBtn'), $host, [
        'class'=>'btn btn-primary form-control',
        'style' => ''
        ]) !!}
    </div>
    {{--<div class="col-sm-3" style="margin: 20px 0px">--}}
        {{--{!! link_to_route('delivery.index', trans('delivery.pedidos.finalizarBtn'), $host, [--}}
        {{--'class'=>'btn btn-success form-control',--}}
        {{--'style' => ''--}}
        {{--]) !!}--}}
    {{--</div>--}}
</div>
