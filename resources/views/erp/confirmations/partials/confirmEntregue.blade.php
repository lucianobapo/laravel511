<div class="row well">
    {!! Form::open([
    'url'=>route('confirmations.postConfirm', [$host]),
    'id' => 'form_entregue'.$order->id,
    'method' => 'POST',
    ]) !!}

    <!-- order Form Input -->
    {!! Form::hidden('order_id',$order->id) !!}
    {!! Form::hidden('type','entregue') !!}

    <!-- mensagem Form Input -->
    <div class="form-group">
        {!! Form::label('mensagem',trans('confirmation.confirm.entregue.label')) !!}
        {!! Form::text('mensagem',null,['class'=>'form-control numbersOnly','placeholder'=>trans('confirmation.confirm.entregue.msg')]) !!}
    </div>

    {!! link_to('#',trans('confirmation.confirm.entregue.btn'),['class'=>'col-sm-4 btn btn-success','send-delete'=>'_entregue'.$order->id]) !!}

    {!! Form::close() !!}
</div>