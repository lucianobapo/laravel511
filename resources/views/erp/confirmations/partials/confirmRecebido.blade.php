<div class="row well">
    {!! Form::open([
    'url'=>route('confirmations.postConfirm', [$host]),
    'id' => 'form'.$order->id,
    'method' => 'POST',
    ]) !!}

    <!-- order Form Input -->
    {!! Form::hidden('order_id',$order->id) !!}
    {!! Form::hidden('type','recebido') !!}
    <p>
        {{ $order->partner->contact_list }}
    </p>

    @if(stripos($order->partner->contact_list,'Email')!==false)

        <!-- mensagem Form Input -->
        <div class="form-group">
            {!! Form::label('enviar',trans('confirmation.confirm.recebido.enviarMensagem')) !!}
            {!! Form::checkbox('enviar', true, ['checked']) !!}
        </div>

        <div class="row">
            <!-- posted_at Form Input -->
            <div class="form-group col-sm-6">
                {!! Form::label('posted_at',trans('confirmation.confirm.entregando.posted_at')) !!}
                {!! Form::input('datetime-local','posted_at',$order->today,['class'=>'form-control', 'required']) !!}
            </div>
            <!-- mensagem Form Input -->
            <div class="form-group col-sm-6">
                {!! Form::label('mensagem',trans('confirmation.confirm.recebido.label')) !!}
                {!! Form::text('mensagem',trans('confirmation.confirm.recebido.msg'),['class'=>'form-control']) !!}
            </div>
        </div>

    @endif

    {{--{!! link_to_route('confirmations.postConfirm',trans('confirmation.btn.recebido'),[$host,$order->id],['class'=>'col-sm-4 btn btn-success']) !!}--}}
    {!! link_to('#',trans('confirmation.confirm.recebido.btn'),['class'=>'col-sm-4 btn btn-success','send-delete'=>$order->id]) !!}

    {!! Form::close() !!}
</div>