@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('confirmation.confirm.title', ['ordem'=>$order->id]) }}</h1>
    <hr>
    <ul>
        @foreach($confirmations as $confirmation)
            <li>{{ $confirmation->created_at }} - {{ $confirmation->type }}</li>
        @endforeach
    </ul>
    <div class="row well" ng-app="myApp">
        {!! Form::open([
        'url'=>route('confirmations.postConfirm', [$host]),
        'id' => 'form'.$order->id,
        'method' => 'POST',
        ]) !!}

        <!-- order Form Input -->
        {!! Form::hidden('order_id',$order->id) !!}
        {!! Form::hidden('type','recebido') !!}

        {{--{!! link_to_route('confirmations.postConfirm',trans('confirmation.btn.recebido'),[$host,$order->id],['class'=>'col-sm-4 btn btn-success']) !!}--}}
        {!! link_to('#',trans('confirmation.btn.recebido'),['class'=>'col-sm-4 btn btn-success','send-delete'=>$order->id]) !!}

        {!! Form::close() !!}
    </div>


@endsection
@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
    </script>

    @include('angular.sendDeleteDirective')
@endsection