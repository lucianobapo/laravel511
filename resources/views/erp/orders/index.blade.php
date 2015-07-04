@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('order.title') }}</h1>
    <hr>
    <div class="pull-right">
        {!! link_to_route('orders.create', trans('order.create.createOrderBtn'), $host, [
            'class'=>'btn btn-primary form-control',
            'style' => ''
        ]) !!}
    </div>
    @if(count($orders))
        <table class="table table-hover table-striped table-condensed" ng-app="myApp">
            <thead>
            <tr>
{{--                <th>{{ trans('modelOrder.attributes.id') }}</th>--}}
                <th>{!! link_to_route_sort_by('orders.index', 'id', trans('modelOrder.attributes.id'), $params) !!}</th>
                <th>{{ trans('modelPartner.attributes.nome') }}</th>
                {{--<th>{{ trans('modelOrder.attributes.posted_at') }}</th>--}}
                <th>{!! link_to_route_sort_by('orders.index', 'posted_at', trans('modelOrder.attributes.posted_at'), $params) !!}</th>
                {{--<th>{{ trans('modelOrder.attributes.valor_total') }}</th>--}}
                <th>{!! link_to_route_sort_by('orders.index', 'valor_total', trans('modelOrder.attributes.valor_total'), $params) !!}</th>
                <th>{{ trans('modelOrder.attributes.currency') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_type_id') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_payment_id') }}</th>
                <th>{{ trans('modelOrder.attributes.status') }}</th>
                <th>{{ trans('order.actionTitle') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->partner->nome }}</td>
                    <td>{{ $order->posted_at }}</td>
                    <td>{{ formatBRL($order->valor_total) }}</td>
                    <td>{{ $order->currency->nome_universal }}</td>
                    <td>{{ $order->type->descricao }}</td>
                    <td>{{ $order->payment->descricao }}</td>
                    <td>{{ $order->status_list }}</td>
                    <td>
                        <div style="width: 90px" class="">
                            @if(stripos($order->status_list,'Finalizado')===false)
                                {!! sprintf( link_to_route('orders.edit', '%s', [$host,$order->id], [
                                'title'=>trans('order.actionEditTitle'),
                                ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}
                            @endif

                            {!! Form::open([
                            'url'=>route('orders.destroy', [$host,$order->id]),
                            'id' => 'form'.$order->id,
                            'method' => 'DELETE',
                            'style' => 'display: inline;',
                            ]) !!}

                            {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('order.actionDeleteTitle'),
                            'send-delete'=>$order->id,
                            ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-sm"></span>' ) !!}

                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
                @if(count($order->orderItems))
                    @if(!is_null($order->address))
                        <tr>
                            <td class="text-right">{{ trans('order.listaEndereco').':' }}</td>
                            <td colspan="7">
                                {{ $order->address->endereco }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="text-right">{{ trans('order.listaItens').':' }}</td>
                        <td colspan="7">
                            @include('erp.orders.partials.itemOrder')
                        </td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        {!! $orders->render() !!}
    @else
        <div class="text-center">
            <em>{{ trans('order.empty') }}</em>
        </div>
    @endif
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);

    </script>

    @include('angular.sendDeleteDirective')
@endsection