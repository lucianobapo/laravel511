@extends('erp.app')

{{--@section('headScriptCss')--}}
    {{--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />--}}
{{--@endsection--}}

@section('content')
    <h1 class="h1s">{{ trans('cost.title') }}</h1>
    <hr>
    @include ('errors.list')
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
        <tr>
            <th>{!! link_to_route_sort_by('costs.index', 'id', trans('modelCostAllocate.attributes.id'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('costs.index', 'nome', trans('modelCostAllocate.attributes.nome'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('costs.index', 'numero', trans('modelCostAllocate.attributes.numero'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('costs.index', 'descricao', trans('modelCostAllocate.attributes.descricao'), $params) !!}</th>
            <th>{{ trans('cost.actionTitle') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            {!! Form::model($costAllocate, [
            'method'=>$method,
            'url'=>route($route, isset($costAllocate->id)?[$host,$costAllocate->id]:$host),
            ]) !!}
            <!-- method Form Input -->
            {!! Form::hidden('method',$method) !!}
            <td></td>
            <td>{!! Form::text('nome', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::text('numero', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::text('descricao', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::submit($submitButtonText, ['class'=>'form-control btn btn-primary']) !!}</td>
            {!! Form::close() !!}
        </tr>
        @if(count($costs))
            @foreach($costs as $cost)
                <tr>
                    <td>{{ $cost->id }}</td>
                    <td>{{ $cost->nome }}</td>
                    <td>{{ $cost->numero }}</td>
                    <td>{{ $cost->descricao }}</td>
                    <td>
                        <div style="width: 90px">
                            {!! Form::open([
                            'url'=>route('costs.destroy', [$host,$cost->id]),
                            'id' => 'form'.$cost->id,
                            'method' => 'DELETE',
                            ]) !!}

                            {!! sprintf( link_to_route('costs.edit', '%s', [$cost->id]+$params, [
                            'title'=>trans('cost.actionEditTitle'),
                            ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}

                            {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('cost.actionDeleteTitle'),
                            'send-delete'=>$cost->id,
                            ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-sm"></span>' ) !!}

                            {!! Form::close() !!}
                        </div>

                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center"><em>{{ trans('cost.empty') }}</em></td>
            </tr>
        @endif


        </tbody>
    </table>
    {!! $costs->render() !!}



@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
    </script>

    @include('angular.sendDeleteDirective')
    {{--@include('angular.selectDirective')--}}
@endsection