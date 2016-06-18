<div class="row well">
    {!! Form::open([
    'url'=>route('confirmations.postConfirm', isset($host)?[$host]:null),
    'id' => 'form_entregue'.$order->id,
    'method' => 'POST',
    ]) !!}

    <!-- order Form Input -->
    {!! Form::hidden('order_id',$order->id) !!}
    {!! Form::hidden('type','entregue') !!}

    <div class="row">
        <!-- posted_at Form Input -->
        <div class="form-group col-sm-6">
            {!! Form::label('posted_at',trans('confirmation.confirm.entregando.posted_at')) !!}
            {!! Form::input('datetime-local','posted_at',$order->today,['class'=>'form-control', 'required']) !!}
        </div>
        <!-- mensagem Form Input -->
        <div class="form-group col-sm-6">
            {!! Form::label('mensagem',trans('confirmation.confirm.entregue.label')) !!}
            {!! Form::input('number','mensagem',null,['class'=>'form-control numbersOnly','placeholder'=>trans('confirmation.confirm.entregue.msg')]) !!}
        </div>
    </div>


    {!! link_to('#',trans('confirmation.confirm.entregue.btn'),['class'=>'col-sm-4 btn btn-success','send-delete'=>'_entregue'.$order->id]) !!}

    {!! Form::close() !!}
</div>