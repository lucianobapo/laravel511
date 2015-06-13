<div ng-show="active=='itens'">
    <div class="row">
        <!-- Product_id Form Input -->
        <div class="form-group col-sm-4">
            {!! Form::label('product_id',trans('modelItemOrder.attributes.product_id')) !!}
        </div>
        <!-- cost_id Form Input -->
        <div class="form-group col-sm-2">
            {!! Form::label('cost_id',trans('modelItemOrder.attributes.cost_id')) !!}
        </div>
        <!-- Quantidade Form Input -->
        <div class="form-group col-sm-2">
            {!! Form::label('quantidade',trans('modelItemOrder.attributes.quantidade')) !!}
        </div>
        <!-- Valor_unitario Form Input -->
        <div class="form-group col-sm-2">
            {!! Form::label('valor_unitario',trans('modelItemOrder.attributes.valor_unitario')) !!}
        </div>
        <!-- currency_list Form Input -->
        <div class="form-group col-sm-2">
            {!! Form::label('item_currency_id',trans('modelItemOrder.attributes.currency')) !!}
        </div>
    </div>

{{--    @for($i=0;$i<$itemCount;$i++)--}}

    <?php $i=0; ?>
    @foreach($itemOrders as $item)
        <div class="row">
            <!--  Form Input -->
            {!! Form::input('hidden','id'.$i,$item->id) !!}

            <!-- Product_id Form Input -->
            <div class="form-group col-sm-4">
                {!! Form::select('product_id'.$i, $product_list,$item->product_id,[
                'class'=>'form-control',
                'product-click'=>$i,
                'select2' => trans('order.create.selecioneProduto')
                ]) !!}
            </div>
            <!-- cost_id Form Input -->
            <div class="form-group col-sm-2">
                {!! Form::select('cost_id'.$i, $costs,$item->cost_id,[
                'class'=>'form-control',
                'select2' => trans('order.create.selecioneCusto')
                ]) !!}
            </div>
            <!-- Quantidade Form Input -->
            <div class="form-group col-sm-2">
                {!! Form::text('quantidade'.$i,$item->quantidade,[
                'class'=>'form-control'
                ]) !!}
            </div>
            <!-- Valor_unitario Form Input -->
            <div class="form-group col-sm-2">
                {!! Form::text('valor_unitario'.$i,$item->valor_unitario,[
                'class'=>'form-control',
                ]) !!}
            </div>
            <!-- currency_list Form Input -->
            <div class="form-group col-sm-2">
                {!! Form::select('item_currency_id'.$i, $currencies, $item->currency_id, ['class'=>'form-control select2']) !!}
            </div>
        </div>
        <?php $i++; ?>
    @endforeach
</div>