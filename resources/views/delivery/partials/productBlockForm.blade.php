@if(config('app.deliveryOpen'))
    <div class="form-group col-sm-6" style="padding: 5px;">
        {!! Form::input('hidden','valor['.$product->id.']', ($product->promocao?$product->valorUnitVendaPromocao:$product->valorUnitVenda), ['class'=>'pass']) !!}
        {!! Form::input('hidden','nome['.$product->id.']', $product->nome, ['class'=>'pass']) !!}

        @if(isset($estoque[$product->id])&&($estoque[$product->id]>0))
            {!! Form::input('number','quantidade['.$product->id.']', 0, [
            'class'=>'form-control pass tooltiped',
            'min'=>0,
            'max'=> $estoque[$product->id]
            ]) !!}
        @endif


    </div>
    <div class="form-group col-sm-6" style="padding: 5px;">
        {!! Form::submit(trans('delivery.productBlock.formAddButton'),[
        'class'=>'btn btn-primary form-control'
        ]) !!}
    </div>
@else
    <div class="form-group col-sm-12" style="padding: 5px;">
        <em style="color: red">{{ trans('delivery.deliveryFechado.productFormAlias') }}</em>
    </div>
@endif

