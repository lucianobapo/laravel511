<div class="row">
    <div class="col-sm-12">
        <em>{{ trans('delivery.pedidos.cartEmpty') }}</em>
    </div>
    <div class="col-sm-3">
        {!! link_to_route('delivery.index', trans('delivery.pedidos.continueBtn'), $host, ['class'=>'btn btn-primary form-control']) !!}
    </div>
</div>
