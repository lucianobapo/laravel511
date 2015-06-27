<div class="row well">
    {!! Form::open([
    'url'=>route('confirmations.postConfirm', [$host]),
    'id' => 'form_entregando'.$order->id,
    'method' => 'POST',
    ]) !!}

    <!-- order Form Input -->
    {!! Form::hidden('order_id',$order->id) !!}
    {!! Form::hidden('type','entregando') !!}

    <!-- mensagem Form Input -->
    <div class="form-group">
        {!! Form::label('mensagem',trans('confirmation.confirm.entregando.label')) !!}
        {!! Form::text('mensagem',null,['class'=>'form-control numbersOnly','placeholder'=>trans('confirmation.confirm.entregando.msg')]) !!}
    </div>

    {!! link_to('#',trans('confirmation.confirm.entregando.btn'),['class'=>'col-sm-4 btn btn-success','send-delete'=>'_entregando'.$order->id]) !!}

    {!! Form::close() !!}
</div>