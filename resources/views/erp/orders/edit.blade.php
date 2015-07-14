@extends('erp.app')

@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <h1>{{ trans('order.update.title') }}</h1>
    <hr>

    @include('erp.orders.partials.pillsNav')

    @include ('errors.list')
    {!! Form::model($order, ['method'=>'PATCH','url'=>route('orders.update', [$host,$order->id]),
        'ng-app'=>"myApp",
        'ng-controller'=>"myCtrl",
    ]) !!}
        @include ('erp.orders.partials.form', ['submitButtonText'=>trans('order.update.orderBtn'),
            'select2tagStatus'=>'select2tag',
            'postedAtInit'=>$order->posted_at_for_field,
        ])
    {!! Form::close() !!}
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
        var products = [];
        var partners = [];
        function formatStateAddress (state) {
            if (!state.id) { return state.text; }
            var $state = $(
                    '<span>' + state.text + '<br> - {{ trans('modelPartner.getPartnerList') }}: '+partners[state.element.value]+'</span>'
//                    '<span><img src="vendor/images/flags/' + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
            );
            return $state;
        };
        app.controller('myCtrl', function($scope) {
            @foreach($products as $product)
                products [{{ $product->id }}] = {
                    nome: '{{ $product->nome }}',
                    valor_venda: {{ $product->promocao?$product->valorUnitVendaPromocao:$product->valorUnitVenda }},
                    valor_compra: {{ $product->valorUnitCompra }},
                    cost_id: {{ is_null($costId = $product->cost_id)?'null':$costId }}
                };
            @endforeach

            @foreach($partners as $partner)
                partners [{{ $partner->id }}] = "{{ (!count($partner->addresses))?trans('order.semEndereco'):$partner->addresses[0]->endereco }}";
            @endforeach
        });
    </script>

    @include('angular.clickOnceDirective')
    @include('angular.selectDirective')
@endsection