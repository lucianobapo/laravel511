@extends('erp.app')

@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection

@section('contentWide')
    <h1 class="h1s">{{ trans('address.title') }}</h1>
    <hr>
    @include ('errors.list')
    <table class="table table-hover table-striped table-condensed" ng-app="myApp">
        <thead>
        <tr>
            <th>{!! link_to_route_sort_by('addresses.index', 'id', trans('modelAddress.attributes.id'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('addresses.index', 'partner_id', trans('modelAddress.attributes.partner_id'), $params) !!}</th>
            <th class="col-sm-1">{!! link_to_route_sort_by('addresses.index', 'cep', trans('modelAddress.attributes.cep'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('addresses.index', 'logradouro', trans('modelAddress.attributes.logradouro'), $params) !!}</th>
            <th class="col-sm-1">{!! link_to_route_sort_by('addresses.index', 'numero', trans('modelAddress.attributes.numero'), $params) !!}</th>
            <th class="col-sm-1">{!! link_to_route_sort_by('addresses.index', 'complemento', trans('modelAddress.attributes.complemento'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('addresses.index', 'bairro', trans('modelAddress.attributes.bairro'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('addresses.index', 'cidade', trans('modelAddress.attributes.cidade'), $params) !!}</th>
            <th class="col-sm-1">{!! link_to_route_sort_by('addresses.index', 'estado', trans('modelAddress.attributes.estado'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('addresses.index', 'obs', trans('modelAddress.attributes.obs'), $params) !!}</th>
            <th>{{ trans('address.actionTitle') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            {!! Form::model($address, [
            'method'=>$method,
            'url'=>route($route, isset($address->id)?[$host,$address->id]:$host),
            ]) !!}
            <!-- method Form Input -->
            {!! Form::hidden('method',$method) !!}
            <td></td>
            <td>{!! Form::select('partner_id', $partners, $partner_selected, ['class'=>'form-control select2', 'required']) !!}</td>
            <td>{!! Form::text('cep', null, ['maxlength'=>8, 'class'=>'form-control numbersOnly cep', 'required']) !!}</td>
            <td>{!! Form::text('logradouro', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::text('numero', null, ['class'=>'form-control', 'required']) !!}</td>
            <td>{!! Form::text('complemento', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('bairro', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('cidade', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('estado', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('obs', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::submit($submitButtonText, ['class'=>'form-control btn btn-primary']) !!}</td>
            {!! Form::close() !!}
        </tr>
        @if(count($addresses))
            @foreach($addresses as $address)
                <tr>
                    <td>{{ $address->id }}</td>
                    <td>{{ $address->partner->nome }}</td>
                    <td>{{ $address->cep }}</td>
                    <td>{{ $address->logradouro }}</td>
                    <td>{{ $address->numero }}</td>
                    <td>{{ $address->complemento }}</td>
                    <td>{{ $address->bairro }}</td>
                    <td>{{ $address->cidade }}</td>
                    <td>{{ $address->estado }}</td>
                    <td>{{ $address->obs }}</td>
                    <td>
                        <div style="width: 90px">
                            {!! Form::open([
                            'url'=>route('addresses.destroy', [$host,$address->id]),
                            'id' => 'form'.$address->id,
                            'method' => 'DELETE',
                            ]) !!}

                            {!! sprintf( link_to_route('addresses.edit', '%s', [$address->id]+$params, [
                            'title'=>trans('address.actionEditTitle'),
                            ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}

                            {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('address.actionDeleteTitle'),
                            'send-delete'=>$address->id,
                            ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-sm"></span>' ) !!}

                            {!! Form::close() !!}
                        </div>

                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center"><em>{{ trans('address.empty') }}</em></td>
            </tr>
        @endif


        </tbody>
    </table>
    {!! $addresses->render() !!}



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