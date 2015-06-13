@include ('errors.list')
{!! Form::open([
    'url'=>route('delivery.addOrder', $host),
    'id'=>'form-add-setting',
    'data-toggle'=>"validator",
    'ng-app'=>"myApp",
    'ng-controller'=>"myCtrl",
]) !!}
{!! Form::hidden('total',$totalCartUnformatted) !!}

@if(!Auth::guest())
    {!! Form::hidden('user_id',Auth::user()->id) !!}
@endif

@foreach($cart as $row)
    {{--{!! Form::hidden('id[]',$row['id']) !!}--}}
    {!! Form::hidden('quantidade['.$row['id'].']',$row['qty']) !!}
    {!! Form::hidden('valor_unitario['.$row['id'].']',$row['price']) !!}
@endforeach

<div>
    <em><span style="color:red;">*</span> {{ trans('delivery.pedidos.form.requiredTag') }}</em>
    <h4 class="h4s">{{ trans('delivery.pedidos.form.formaPagamento') }}:</h4><hr>
</div>
<div class="row">
    <!-- Pagamento Form Input -->
    <div class="form-group col-sm-12" data-toggle="buttons">
        {!! labelEx('pagamentoDinheiro', Form::radio('pagamento','vistad',true,['id'=>'pagamentoDinheiro']).
        trans('modelPartner.attributes.pagamentoDinheiro') , ['class' => 'btn btn-default active radioButtonMobile']) !!}

        {!! labelEx('pagamentoCartaoDebito', Form::radio('pagamento','vistacd',null,['id'=>'pagamentoCartaoDebito']).
        trans('modelPartner.attributes.pagamentoCartaoDebito'), ['class' => 'btn btn-default radioButtonMobile']) !!}

        {!! labelEx('pagamentoCartaoCredito', Form::radio('pagamento','vistacc',null,['id'=>'pagamentoCartaoCredito']).
        trans('modelPartner.attributes.pagamentoCartaoCredito'), ['class' => 'btn btn-default radioButtonMobile']) !!}
    </div>
</div>
<div class="row">
    <!-- Troco Form Input -->
    <div class="form-group col-sm-4">
        {!! Form::label('troco',trans('modelPartner.attributes.troco')) !!}
        {!! Form::text('troco',null,[
            'class'=>'form-control',
            'placeholder'=>trans('delivery.pedidos.form.placeholder.troco'),
        ]) !!}
    </div>
</div>

<div class="{{ (Auth::guest())?'':' hide' }}">
    <h4 class="h4s">{{ trans('delivery.pedidos.form.dadosPessoais') }}:</h4><hr>
</div>
<div class="row{{ (Auth::guest())?'':' hide' }}">
    <!-- Nome Form Input -->
    <div class="form-group col-sm-6">
        {!! labelEx('nome', trans('modelPartner.attributes.nome').' <span style="color:red;">*</span>') !!}
        {!! Form::text('nome', (Auth::guest())?null:Auth::user()->name, [
            'class'=>'form-control',
            Auth::guest()?'enabled':'enabled',
        ]) !!}
    </div>
    <!-- Tax_id Form Input -->
    <div class="col-sm-3">
        {!! Form::label('tax_id',trans('modelPartner.attributes.tax_id')) !!}
        {!! Form::text('tax_id',null,['class'=>'form-control']) !!}
    </div>
    <!-- Data_de_nascimento Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('data_nascimento',trans('modelPartner.attributes.data_nascimento')) !!}
        {!! Form::input('date','data_nascimento',null,['class'=>'form-control', 'maxlength'=>10, 'placeholder'=>trans('delivery.pedidos.form.placeholder.data_nascimento')]) !!}
    </div>
</div>

<div class="{{ (Auth::guest())?'':' hide' }}">
    <h4 class="h4s">{{ trans('delivery.pedidos.form.dadosContato') }}:</h4><hr>
</div>
<div class="row{{ (Auth::guest())?'':' hide' }}">
    <!-- Email Form Input -->
    <div class="form-group col-sm-6">
        {!! labelEx('email',trans('modelPartner.attributes.email').' <span style="color:red;">*</span>') !!}
        {!! Form::input('email','email', (Auth::guest())?null:Auth::user()->email,[
            'class'=>'form-control',
            Auth::guest()?'enabled':'enabled',
        ]) !!}
    </div>
    <!-- Telefone Form Input -->
    <div class="form-group col-sm-6">
        {!! labelEx('telefone',trans('modelPartner.attributes.telefone').' <span style="color:red;">*</span>') !!}
        {!! Form::text('telefone',null,['class'=>'form-control']) !!}
    </div>
</div>

<div>
    <h4 class="h4s">{{ trans('delivery.pedidos.form.dadosEntrega') }}:</h4><hr>
</div>

{!! $panelListaEnderecos !!}

<div class="row" ng-hide="oldAddress">
    <!-- Cep Form Input -->
    <div class="form-group col-sm-3">
        {!! labelEx('cep',trans('modelPartner.attributes.cep').' <span style="color:red;">*</span>') !!}
        {!! Form::text('cep',null,['class'=>'form-control',
            'ng-disabled'=>"oldAddress",
            'placeholder'=>trans('delivery.pedidos.form.placeholder.cep')]) !!}
    </div>
    <!-- Logradouro Form Input -->
    <div class="form-group col-sm-5">
        {!! labelEx('logradouro',trans('modelPartner.attributes.logradouro').' <span style="color:red;">*</span>') !!}
        {!! Form::text('logradouro',null,['class'=>'form-control',
            'ng-disabled'=>"oldAddress",]) !!}
    </div>
    <!-- Numero Form Input -->
    <div class="form-group col-sm-2">
        {!! labelEx('numero',trans('modelPartner.attributes.numero').' <span style="color:red;">*</span>') !!}
        {!! Form::text('numero',null,['class'=>'form-control', 'ng-disabled'=>"oldAddress",]) !!}
    </div>
    <!-- Complemento Form Input -->
    <div class="form-group col-sm-2">
        {!! Form::label('complemento',trans('modelPartner.attributes.complemento')) !!}
        {!! Form::text('complemento',null,['class'=>'form-control']) !!}
    </div>
</div>

<div class="row" ng-hide="oldAddress">
    <!-- Bairro Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('bairro',trans('modelPartner.attributes.bairro')) !!}
        {!! Form::text('bairro',null,['class'=>'form-control']) !!}
    </div>
    <!-- Cidade Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::label('cidade',trans('modelPartner.attributes.cidade')) !!}
        {!! Form::text('cidade',null,['class'=>'form-control']) !!}
    </div>
    <!-- Estado Form Input -->
    <div class="form-group col-sm-2">
        {!! Form::label('estado',trans('modelPartner.attributes.estado')) !!}
        {!! Form::text('estado',null,['class'=>'form-control']) !!}
    </div>
    <!-- Observacao Form Input -->
    <div class="form-group col-sm-4">
        {!! Form::label('observacao',trans('modelPartner.attributes.observacao')) !!}
        {!! Form::text('observacao',null,['class'=>'form-control']) !!}
    </div>
</div>
<div class="row">
    <!-- Finalizar Form Input -->
    <div class="form-group col-sm-3">
        {!! Form::submit(trans('delivery.pedidos.finalizarBtn'),['class'=>'btn btn-success form-control', 'click-once'=>trans('delivery.clickOnce')]) !!}
    </div>
</div>


{!! Form::close() !!}