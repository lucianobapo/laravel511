@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('confirmation.title') }}</h1>
    <hr>
    @if(count($orders))
        <table class="table table-hover table-striped table-condensed" ng-app="myApp">
            <thead>
            <tr>
                <th>{{ trans('modelOrder.attributes.id') }}</th>
                <th>{{ trans('modelPartner.attributes.nome') }}</th>
                <th>{{ trans('modelOrder.attributes.posted_at') }}</th>
                <th>{{ trans('modelOrder.attributes.valor_total') }}</th>
                <th>{{ trans('modelOrder.attributes.currency') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_type_id') }}</th>
                <th>{{ trans('modelOrder.attributes.shared_order_payment_id') }}</th>
                <th>{{ trans('modelOrder.attributes.status') }}</th>
                <th>{{ trans('confirmation.actionTitle') }}</th>
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
                        {!! sprintf( link_to_route('orders.edit', '%s', [$host,$order->id], [
                        'title'=>trans('confirmation.actionEditTitle'),
                        ]), '<span style="margin-right: 15px" class="glyphicon glyphicon-pencil btn btn-default btn-xs"></span>' ) !!}

                        {!! sprintf( link_to_route('confirmations.getConfirm', '%s', [$host,$order->id], [
                        'title'=>trans('confirmation.actionConfirmTitle'),
                        ]), '<span class="glyphicon glyphicon-flag btn btn-default btn-xs"></span>' ) !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center">
            <em>{{ trans('order.empty') }}</em>
        </div>
    @endif
@endsection