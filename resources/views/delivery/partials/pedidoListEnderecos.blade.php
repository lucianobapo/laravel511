<div>{{ trans('delivery.pedidos.form.oldAddress') }}</div>
<div class="row">
    <div class="form-group col-sm-12" data-toggle="buttons">
        @foreach($enderecos as $endereco)
            {!! labelEx('endereco_'.$endereco->id, Form::radio('address_id',$endereco->id,false,['id'=>'endereco_'.$endereco->id]).
            ' - '.$endereco->logradouro.', '.$endereco->numero
            .(empty($endereco->complemento)?'':' - '.$endereco->complemento)
            .(empty($endereco->bairro)?'':' - '.$endereco->bairro)
            .(empty($endereco->cep)?'':' - CEP: '.$endereco->cep)
            .(empty($endereco->cidade)?'':' - '.$endereco->cidade)
            .(empty($endereco->estado)?'':'/'.$endereco->estado) , [
                'class' => 'form-control btn btn-default radioButtonMobile',
                'style' => 'text-align: left;',
                'ng-click'=>"oldAddress=true",
            ]) !!}
        @endforeach
        {!! labelEx('novo', Form::radio('address_id','novo',true,['id'=>'novo']).trans('delivery.pedidos.form.createAddress') , [
            'class' => 'form-control btn btn-default active radioButtonMobile',
            'style' => 'text-align: left;',
            'ng-init'=>"oldAddress=false",
            'ng-click'=>"oldAddress=false",
            ]) !!}
    </div>
</div>
