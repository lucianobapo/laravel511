@extends('erp.app')
@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <h1 class="h1s">{{ trans('product.title') }}</h1>
    <hr>
    @include ('errors.list')
    <table class="table table-hover table-condensed" ng-app="myApp">
        <thead>
        <tr>
{{--            <th>{{ trans('modelProduct.attributes.id') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'id', trans('modelProduct.attributes.id'), $params) !!}</th>
            {{--<th class="col-sm-2">{{ trans('modelProduct.attributes.nome') }}</th>--}}
            <th class="col-sm-2">{!! link_to_route_sort_by('products.index', 'nome', trans('modelProduct.attributes.nome'), $params) !!}</th>
            {{--<th>{{ trans('modelProduct.attributes.imagem') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'imagem', trans('modelProduct.attributes.imagem'), $params) !!}</th>
{{--            <th>{{ trans('modelProduct.attributes.promocao') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'promocao', trans('modelProduct.attributes.promocao'), $params) !!}</th>
            <th class="col-sm-1">{{ trans('modelProduct.attributes.grupos') }}</th>
{{--            <th>{{ trans('modelProduct.attributes.valorUnitVenda') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'valorUnitVenda', trans('modelProduct.attributes.valorUnitVenda'), $params) !!}</th>
            {{--<th>{{ trans('modelProduct.attributes.valorUnitVendaPromocao') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'valorUnitVendaPromocao', trans('modelProduct.attributes.valorUnitVendaPromocao'), $params) !!}</th>
            {{--<th>{{ trans('modelProduct.attributes.valorUnitCompra') }}</th>--}}
            <th>{!! link_to_route_sort_by('products.index', 'valorUnitCompra', trans('modelProduct.attributes.valorUnitCompra'), $params) !!}</th>
            <th>{!! link_to_route_sort_by('products.index', 'estoque', trans('modelProduct.attributes.estoque'), $params) !!}</th>
            <th class="col-sm-1">{{ trans('modelProduct.attributes.status') }}</th>
            <th>{{ trans('product.actionTitle') }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            {!! Form::open([
                'url'=>route('products.store', $host),
                'files' => true,
            ]) !!}
            <td></td>
            <td>{!! Form::text('nome', null, ['class'=>'form-control', 'required'=>true]) !!}</td>
            <td>{!! Form::file('imagem', ['class'=>'form-control', 'accept'=>'.png']) !!}</td>
            <td>{!! Form::checkbox('promocao', 1, null, ['class'=>'']) !!}</td>
            <td>{!! Form::select('grupos[]', $grupos, null, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
            <td>{!! Form::text('valorUnitVenda', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('valorUnitVendaPromocao', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::text('valorUnitCompra', null, ['class'=>'form-control']) !!}</td>
            <td>{!! Form::checkbox('promocao', 1, null, ['class'=>'']) !!}</td>
            <td>{!! Form::select('status[]', $status, null, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
            <td>{!! Form::submit(trans('product.actionBtn'), ['class'=>'form-control btn btn-primary']) !!}</td>
            {!! Form::close() !!}
        </tr>
        @if(count($products))
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->nome }}</td>
                    <td>{{ $product->imagem }}</td>
                    <td>{{ trans('modelProduct.values.promocao.'.$product->promocao) }}</td>
                    <td>{{ $product->group_list }}</td>
                    <td>{{ formatBRL($product->valorUnitVenda) }}</td>
                    <td>{{ formatBRL($product->valorUnitVendaPromocao) }}</td>
                    <td>{{ formatBRL($product->valorUnitCompra) }}</td>
                    <td>{{ isset($estoque[$product->id])?$estoque[$product->id]:0 }}</td>
                    <td>{{ $product->status_list }}</td>
                    <td>
                        {!! Form::open([
                            'url'=>route('products.destroy', [$host,$product->id]),
                            'id' => 'form'.$product->id,
                            'method' => 'DELETE',
                        ]) !!}
                        {{--{!! link_to('#','<span class="glyphicon glyphicon-remove"></span>', ['title'=>trans('product.actionDeleteTitle')]) !!}--}}
                        {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('product.actionDeleteTitle'),
                            'send-delete'=>$product->id,
                        ]), '<span class="glyphicon glyphicon-remove"></span>' ) !!}
                        {{--{!! sprintf( link_to_route('products.destroy', '%s', [$host,$product->id], ['title'=>trans('product.actionDeleteTitle')]), '<span class="glyphicon glyphicon-remove"></span>' ) !!}--}}

                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center"><em>{{ trans('product.empty') }}</em></td>
            </tr>
        @endif
        </tbody>
    </table>
    {!! $products->render() !!}
@endsection

@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>

    <script type="text/javascript">
        var app = angular.module('myApp', []);
//        app.controller('myCtrl', function($scope) {
//            //            $scope.firstName= "John";
//            //            $scope.lastName= "Doe";
////            $scope.list = [];
////            $scope.text = 'hello';
//            $scope.submit = function() {
//                if ($scope.text) {
//                    $scope.list.push(this.text);
//                    $scope.text = '';
//                }
//            };
//        });
    </script>

    @include('angular.sendDeleteDirective')
    @include('angular.selectDirective')
@endsection