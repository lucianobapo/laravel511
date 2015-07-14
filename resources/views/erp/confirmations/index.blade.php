@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('confirmation.title') }}</h1>
    <hr>

    @include('erp.orders.partials.pillsNav')

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
                        <div style="width: 90px">
                            {!! sprintf( link_to_route('orders.edit', '%s', [$host,$order->id], [
                            'title'=>trans('confirmation.actionEditTitle'),
                            ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}

                            {!! sprintf( link_to_route('confirmations.getConfirm', '%s', [$host,$order->id], [
                            'title'=>trans('confirmation.actionConfirmTitle'),
                            ]), '<span class="glyphicon glyphicon-flag btn btn-default btn-sm"></span>' ) !!}
                        </div>

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