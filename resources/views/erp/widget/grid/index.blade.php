@extends('erp.app')

@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection

@section($gridType)
    <h1 class="h1s">{{ $gridTitle }}</h1>
    <hr>
    @include ('errors.list')
    {!! $items->render() !!}
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
        <tr>
            @foreach($columns as $column)
                @if(is_array($column))
                    <th class="{{ isset($column['thClass'])?$column['thClass']:'' }}">
                        @if(isset($column['sort']) && !$column['sort'])
                            {{ trans( isset($column['customTitle'])?$column['customTitle']:$modelTrans.$column['name'] ) }}
                        @else
                            {!! link_to_route_sort_by($route['index'], $column['name'], trans($modelTrans.$column['name']), $params) !!}
                        @endif
                    </th>
                @else
                    <th>{!! link_to_route_sort_by($route['index'], $column, trans($modelTrans.$column), $params) !!}</th>
                @endif
            @endforeach
            <th>{{ $actionTitle }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>

            {!! Form::model($model, [
            'method'=>$method,
            'url'=>route($route['form'], isset($model->id)?[$model->id]+$params:$params),
            ]) !!}
            <!-- method Form Input -->
            {!! Form::hidden('method',$method) !!}
            @foreach($columns as $column)
                @if(is_array($column) && isset($column['inputDisabled']) && $column['inputDisabled'])
                    <td></td>
                @elseif(is_array($column) && isset($column['inputType']) && ($column['inputType']=='select') )
                    <td>{!! Form::select($column['name'], $column['selectList'], $column['selectedItem'], isset($column['attributes'])?$column['attributes']:['class'=>'form-control select2']) !!}</td>
                @elseif(is_array($column))
                    <td>{!! Form::text($column['name'], null, isset($column['attributes'])?$column['attributes']:['class'=>'form-control']) !!}</td>
                @else
                    <td>{!! Form::text($column, null, ['class'=>'form-control']) !!}</td>
                @endif
            @endforeach
            <td>{!! Form::submit($submitButtonText, ['class'=>'form-control btn btn-primary']) !!}</td>
            {!! Form::close() !!}
        </tr>
        @if(count($items))
            @foreach($items as $item)
                <tr>
                    @foreach($columns as $column)
                        @if(is_array($column))
                            <td>{{ isset($column['sub'])?$item->$column['sub']->$column['column']:(isset($column['column'])?$item->$column['column']:$item->$column['name']) }}</td>
                        @else
                            <td>{{ $item->$column }}</td>
                        @endif

                    @endforeach
                    <td>
                        <div style="width: 90px">
                            {!! Form::open([
                            'url'=>route($route['destroy'], [$host,$item->id]),
                            'id' => 'form'.$item->id,
                            'method' => 'DELETE',
                            ]) !!}

                            {!! sprintf( link_to_route($route['edit'], '%s', [$item->id]+$params, [
                            'title'=>$actionEditTitle,
                            ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}

                            {!! sprintf( link_to('#', '%s', [
                            'title'=>$actionDeleteTitle,
                            'send-delete'=>$item->id,
                            ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-sm"></span>' ) !!}

                            {!! Form::close() !!}
                        </div>

                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center"><em>{{ $emptyText }}</em></td>
            </tr>
        @endif


        </tbody>
    </table>
    {!! $items->render() !!}
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
    </script>

    @include('angular.sendDeleteDirective')
    @include('angular.cepDirective')
    @include('angular.numbersOnlyDirective')
    @include('angular.selectDirective')
@endsection