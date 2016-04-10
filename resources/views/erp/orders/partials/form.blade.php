<div class="row">
    <!-- partner_id Form Input -->
    <div class="form-group col-sm-5">
        <div>
            {!! Form::label('partner_id', trans('modelOrder.attributes.partner_id')) !!}
        </div>
        {!! Form::select('partner_id',$partner_list, $order->partner_id,[
            'id' => 'partner_id',
            'class'=>'form-control partner-click',
            'select2partner' => trans('order.create.selecioneParceiro'),
        ]) !!}

    </div>

    <!-- posted_at Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('posted_at', trans('modelOrder.attributes.posted_at')) !!}
        {!! Form::input('datetime-local','posted_at',$postedAtInit,['class'=>'form-control', 'required']) !!}
    </div>

    <!-- currency_list Form Input -->
    <div class="form-group col-sm-2">
        <div>
            {!! Form::label('currency_id',trans('modelOrder.attributes.currency')) !!}
        </div>
        {!! Form::select('currency_id', $currencies, $order->currency_id, ['class'=>'form-control select2']) !!}
    </div>

    <!-- shared_order_type_id Form Input -->
    <div class="form-group col-sm-2">
        <div>
            {!! Form::label('type_id',trans('modelOrder.attributes.shared_order_type_id')) !!}
        </div>
        {!! Form::select('type_id', $order_types, $order->type_id, ['class'=>'form-control select2']) !!}
    </div>
</div>



<div class="row">
    <!-- Descricao Form Input -->
    <div class="form-group col-sm-6">
        {!! Form::label('descricao',trans('modelOrder.attributes.descricao')) !!}
        {!! Form::textarea('descricao',$order->descricao,['class'=>'form-control', 'rows'=>5]) !!}
    </div>

    <!-- shared_order_payment_id Form Input -->
    <div class="form-group col-sm-2">
        <div>
            {!! Form::label('payment_id',trans('modelOrder.attributes.shared_order_payment_id')) !!}
        </div>
        {!! Form::select('payment_id', $order_payment, $order->payment_id, ['class'=>'form-control select2']) !!}
    </div>

    <!-- status Form Input -->
    <div class="form-group col-sm-2">
        <div>
            {!! Form::label('status',trans('modelOrder.attributes.status')) !!}
        </div>
        {!! Form::select('status[]', $status, $order->status()->getRelatedIds()->toArray(), [
            'class'=>'form-control '.$select2tagStatus,
            'multiple'
        ]) !!}
    </div>

    <!-- troco Form Input -->
    <div class="form-group col-sm-2">
        {!! Form::label('troco',trans('modelOrder.attributes.troco')) !!}
        {!! Form::text('troco',$order->troco,['class'=>'form-control','autocomplete'=>'off']) !!}
    </div>

    <!-- Referencia Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('referencia',trans('modelOrder.attributes.referencia')) !!}
        {!! Form::text('referencia',$order->referencia,['class'=>'form-control']) !!}
    </div>

    <!-- Observacao Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('observacao',trans('modelOrder.attributes.observacao')) !!}
        {!! Form::text('observacao',$order->observacao,['class'=>'form-control']) !!}
    </div>

</div>

<ul class="nav nav-tabs" ng-init="active='itens'" style="margin-bottom: 10px">
    <li ng-class="(active=='itens')?'active':''"><a href="#" ng-click="active='itens'">Itens da Ordem</a></li>
    <li ng-class="(active=='consumo')?'active':''"><a href="#" ng-click="active='consumo'">Itens de Consumo</a></li>
    <li ng-class="(active=='pagamento')?'active':''"><a href="#" ng-click="active='pagamento'">Pagamento</a></li>
    <li ng-class="(active=='transporte')?'active':''"><a href="#" ng-click="active='transporte'">Transporte</a></li>
    <li ng-class="(active=='anexos')?'active':''"><a href="#" ng-click="active='anexos'">Anexos</a></li>
</ul>

{!! $viewItemOrderForm !!}
{!! $viewPagamentoForm !!}
{!! $viewConsumoForm !!}
{!! $viewTransporteForm !!}
{!! $viewAnexosForm !!}

<div class="row">
    <!-- Add Button Form Input -->
    <div class="form-group col-sm-12">
        {!! Form::submit($submitButtonText,['class'=>'btn btn-primary form-control']) !!}
    </div>
</div>