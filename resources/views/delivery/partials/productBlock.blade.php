<div class="" style="padding: 5px; height: 150px;">
    {!! Html::image(config('filesystems.imageUrl').$product->imagem,
    trans('delivery.productBlock.imageAlt', ['product' => $product->nome]),
    ['title'=>trans('delivery.productBlock.imageAlt', ['product' => $product->nome]),
    'class'=>'img-responsive center-block']) !!}
</div>
<div class="" style="padding: 5px; height: 100px;">
    <div>
        <strong>{{ $product->nome }}</strong>
    </div>
    @if($product->promocao)
        <div>
            <del style="color: red;">{{ formatBRL($product->valorUnitVenda) }}</del>
        </div>
        <div>
            <strong style="color: #4682b4; font-size: 16px">{{ formatBRL($product->valorUnitVendaPromocao) }}</strong>
        </div>
    @else
        <div>
            <strong style="color: #4682b4; font-size: 16px">{{ formatBRL($product->valorUnitVenda) }}</strong>
        </div>
    @endif
</div>