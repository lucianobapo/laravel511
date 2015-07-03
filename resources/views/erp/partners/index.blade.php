
@extends('erp.app')

@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <h1 class="h1s">{{ trans('partner.title') }}</h1>
    <hr>
    @include ('errors.list')
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
        <tr>
            <th>{!! link_to_route_sort_by('partners.index', 'id', trans('modelPartner.attributes.id'), $params) !!}</th>
            {{--<th>{{ trans('modelPartner.attributes.id') }}</th>--}}
            <th>{!! link_to_route_sort_by('partners.index', 'nome', trans('modelPartner.attributes.nome'), $params) !!}</th>
            {{--<th>{{ trans('modelPartner.attributes.nome') }}</th>--}}
            <th>{!! link_to_route_sort_by('partners.index', 'data_nascimento', trans('modelPartner.attributes.data_nascimento'), $params) !!}</th>
            {{--<th>{{ trans('modelPartner.attributes.data_nascimento') }}</th>--}}
            <th>{{ trans('modelPartner.attributes.observacao') }}</th>
            <th class="col-sm-2">{{ trans('modelPartner.attributes.grupos') }}</th>
            <th class="col-sm-2">{{ trans('modelPartner.attributes.status') }}</th>
            <th>{{ trans('partner.actionTitle') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            {!! Form::model($partner, [
                'method'=>$method,
                'url'=>route($route, isset($partner->id)?[$host,$partner->id]:$host),
                'files' => true,
            ]) !!}
            <!-- method Form Input -->
            {!! Form::hidden('method',$method) !!}
            <td></td>
            <td>{!! Form::text('nome', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::input('date','data_nascimento', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('observacao', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::select('grupos[]', $grupos, $group_selected, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
            <td>{!! Form::select('status[]', $status, $status_selected, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
            <td>{!! Form::submit($submitButtonText, ['class'=>'form-control btn btn-primary']) !!}</td>
            {!! Form::close() !!}
        </tr>
        @if(count($partners))
            @foreach($partners as $partner)
                <tr>
                    <td>{{ $partner->id }}</td>
                    <td>{{ $partner->nome }}</td>
                    <td>{{ $partner->data_nascimento }}</td>
                    <td>{{ $partner->observacao }}</td>
                    <td>{{ $partner->group_list }}</td>
                    <td>{{ $partner->status_list }}</td>
                    <td>
                        {!! Form::open([
                            'url'=>route('partners.destroy', [$host,$partner->id]),
                            'id' => 'form'.$partner->id,
                            'method' => 'DELETE',
                        ]) !!}

                        {!! sprintf( link_to_route('partners.edit', '%s', [$partner->id]+$params, [
                        'title'=>trans('partner.actionEditTitle'),
                        ]), '<span style="margin-right: 15px" class="glyphicon glyphicon-pencil btn btn-default btn-xs"></span>' ) !!}

                        {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('partner.actionDeleteTitle'),
                            'send-delete'=>$partner->id,
                        ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-xs"></span>' ) !!}

                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center"><em>{{ trans('partner.empty') }}</em></td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $partners->render() !!}
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
    </script>

    @include('angular.sendDeleteDirective')
    @include('angular.selectDirective')
@endsection