@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('confirmation.confirm.title', ['ordem'=>$order->id]) }}</h1>
    <hr>
    <ul>
        <li>{{ trans('confirmation.confirm.posted_at') }}: {{ $order->posted_at }}</li>
        @foreach($confirmations as $confirmation)
            <li>{{ $confirmation->created_at }} - {{ $confirmation->type }} - {{ $confirmation->message }}</li>
        @endforeach
    </ul>

    <div ng-app="myApp">
        {!! $viewConfirmRecebido !!}
        {!! $viewConfirmEntregando !!}
        {!! $viewConfirmEntregue !!}
    </div>


@endsection
@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
    </script>

    @include('angular.sendDeleteDirective')
    @include('angular.numbersOnlyDirective')
@endsection