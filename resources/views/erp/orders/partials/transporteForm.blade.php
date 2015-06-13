<div ng-show="active=='transporte'">
    <div class="row">
        <!-- address_id Form Input -->
        <div class="form-group col-sm-5">
            <div>
                {!! Form::label('address_id',trans('modelOrder.attributes.address_id')) !!}
            </div>
            {!! Form::select('address_id', $addresses,$order->address_id,[
            'class'=>'form-control',
            'style' => 'width:100%',
            'select2' => trans('order.create.selecioneEndereco')
            ]) !!}
        </div>
    </div>

</div>