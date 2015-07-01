<div class="row well">
    {!! Form::open([
    'url'=>route('confirmations.postConfirm', [$host]),
    'id' => 'form_entregando'.$order->id,
    'method' => 'POST',
    ]) !!}

    <!-- order Form Input -->
    {!! Form::hidden('order_id',$order->id) !!}
    {!! Form::hidden('type','entregando') !!}

    <div class="row">
        <!-- posted_at Form Input -->
        <div class="form-group col-sm-6">
            {!! Form::label('posted_at',trans('confirmation.confirm.entregando.posted_at')) !!}
            {!! Form::input('datetime-local','posted_at',$order->today,['class'=>'form-control']) !!}
        </div>
        <!-- mensagem Form Input -->
        <div class="form-group col-sm-6">
            {!! Form::label('mensagem',trans('confirmation.confirm.entregando.label')) !!}
            {!! Form::input('number','mensagem',null,['class'=>'form-control numbersOnly','placeholder'=>trans('confirmation.confirm.entregando.msg')]) !!}
        </div>
    </div>


    {!! link_to('#',trans('confirmation.confirm.entregando.btn'),['class'=>'col-sm-4 btn btn-success','send-delete'=>'_entregando'.$order->id]) !!}

    {!! Form::close() !!}
</div>