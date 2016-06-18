@extends('erp.app')
@section('contentWide')
    <h1 class="h1s">{{ trans('order.title') }}</h1>
    <hr>
    @include('erp.orders.partials.pillsNav')
{{--    {{ dd($params) }}--}}
    @if(count($orders))
        {!! getRender($orders) !!}
        <table class="table table-hover table-striped table-condensed" ng-app="myApp">
            <thead>
            <tr>
                <th>{!! is_null($sortRoute)?trans('modelOrder.attributes.id'):link_to_route_sort_by($sortRoute, 'id', trans('modelOrder.attributes.id'), $params) !!}</th>
                <th>{{ trans('modelPartner.attributes.nome') }}</th>
                <th>{!! is_null($sortRoute)?trans('modelOrder.attributes.posted_at'):link_to_route_sort_by($sortRoute, 'posted_at', trans('modelOrder.attributes.posted_at'), $params) !!}</th>
                <th>{!! is_null($sortRoute)?trans('modelOrder.attributes.valor_total'):link_to_route_sort_by($sortRoute, 'valor_total', trans('modelOrder.attributes.valor_total'), $params) !!}</th>
                <th>{!! is_null($sortRoute)?trans('modelOrder.attributes.troco'):link_to_route_sort_by($sortRoute, 'troco', trans('modelOrder.attributes.troco'), $params) !!}</th>
                <th>{{ trans('modelOrder.attributes.currency') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_type_id') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_payment_id') }}</th>
                <th>{{ trans('modelOrder.attributes.status') }}</th>
                <th>{{ trans('order.actionTitle') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->partner->nome }}</td>
                    <td>{{ $order->posted_at }}</td>
                    <td>{{ formatBRL($order->valor_total) }}</td>
                    <td>{{ formatBRL($order->troco) }}</td>
                    <td>{{ $order->currency->nome_universal }}</td>
                    <td>{{ $order->type->descricao }}</td>
                    <td>{{ $order->payment->descricao }}</td>
                    <td>{{ $order->status_list }}</td>
                    <td>
                        <div style="width: 180px" class="" ng-init="detalhes=true">
                            <button title="{{ trans('order.actionDetailsTitle') }}" ng-click="detalhes{{ $order->id }}=!detalhes{{ $order->id }}" style="margin: 0px 10px 0px 0px" class="glyphicon btn btn-default btn-sm" ng-class="{'glyphicon-plus':!detalhes{{ $order->id }},'glyphicon-minus':detalhes{{ $order->id }}}"></button>

                            @if( (stripos($order->status_list,'Finalizado')===false) || (Auth::user()->role->name==config('delivery.rootRole')) )
                                {{--{!! sprintf( link_to_route('orders.edit', '%s', [$order->id,$paramsSerialized], [--}}
                                {{--'title'=>trans('order.actionEditTitle'),--}}
                                {{--]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}--}}
                                {!! (str_replace('%s','<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>',
                                link_to_route('orders.edit', '%s', isset($host)?[$host,$order->id,'paramsSerialized'=>$paramsSerialized]:[$order->id,'paramsSerialized'=>$paramsSerialized], ['title'=>trans('order.actionEditTitle')] ))) !!}
                            @endif

                            @if( (stripos($order->status_list,'Aberto')!==false) )
                                {!! sprintf( link_to_route('confirmations.getConfirm', '%s', isset($host)?[$host,$order->id]:[$order->id], [
                                'title'=>trans('confirmation.actionConfirmTitle'),
                                ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-flag btn btn-default btn-sm"></span>' ) !!}
                            @endif

                            {!! Form::open([
                            'url'=>route('orders.destroy', isset($order->id)?[$order->id]+$params:$params),
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
                <tr ng-show="detalhes{{ $order->id }}">
                    <td colspan="9">
                        <div class="well">
                            @if(!is_null($order->address))
                                <div>{{ trans('order.listaEndereco').': '.$order->address->endereco }}</div>
                            @endif
                            @if(count($order->attachments))
                                <div ng-init="active='anexos'">
                                    @include('erp.orders.partials.anexosForm', isset($host)?['host' => $host, 'attachments' => $order->attachments]:['attachments' => $order->attachments])
                                </div>
                            @endif
                            @if(count($order->orderItems))
                                @include('erp.orders.partials.itemOrder')
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="10"><em>{{ trans('order.empty') }}</em></td>
                </tr>
            @endforelse
            </tbody>
        </table>
        {!! getRender($orders) !!}
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