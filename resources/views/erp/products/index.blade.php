@extends('erp.app')
@section('headScriptCss')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-rc.2/css/select2.min.css" rel="stylesheet" />
@endsection
@section('contentWide')
    <h1 class="h1s">{{ trans('product.title') }}</h1>
    <hr>
    @include ('errors.list')
    {!! $products->render() !!}
    <table class="table table-hover table-striped table-condensed table-responsive" ng-app="myApp">
        <thead>
            <tr>
    {{--            <th>{{ trans('modelProduct.attributes.id') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'id', trans('modelProduct.attributes.id'), $params) !!}</th>
                <th>{!! link_to_route_sort_by('products.index', 'cod_barra', trans('modelProduct.attributes.cod_barra'), $params) !!}</th>
                <th class="col-sm-1">{!! link_to_route_sort_by('products.index', 'cod_fiscal', trans('modelProduct.attributes.cod_fiscal'), $params) !!}</th>
                {{--<th class="col-sm-2">{{ trans('modelProduct.attributes.nome') }}</th>--}}
                <th class="">{!! link_to_route_sort_by('products.index', 'nome', trans('modelProduct.attributes.nome'), $params) !!}</th>
                {{--<th>{{ trans('modelProduct.attributes.imagem') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'imagem', trans('modelProduct.attributes.imagem'), $params) !!}</th>
    {{--            <th>{{ trans('modelProduct.attributes.promocao') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'promocao', trans('modelProduct.attributes.promocaoAbreviado'), $params) !!}</th>
                <th class="col-sm-1">{{ trans('modelProduct.attributes.grupos') }}</th>
                <th class="col-sm-1">{{ trans('modelProduct.attributes.costIdAbreviado') }}</th>
    {{--            <th>{{ trans('modelProduct.attributes.valorUnitVenda') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'valorUnitVenda',
                    trans('modelProduct.attributes.valorUnitVendaAbreviado'), $params,
                    ['title'=>trans('modelProduct.attributes.valorUnitVenda')]) !!}</th>
                {{--<th>{{ trans('modelProduct.attributes.valorUnitVendaPromocao') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'valorUnitVendaPromocao',
                    trans('modelProduct.attributes.valorUnitVendaPromocaoAbreviado'), $params,
                    ['title'=>trans('modelProduct.attributes.valorUnitVendaPromocao')]) !!}</th>
                {{--<th>{{ trans('modelProduct.attributes.valorUnitCompra') }}</th>--}}
                <th>{!! link_to_route_sort_by('products.index', 'valorUnitCompra',
                    trans('modelProduct.attributes.valorUnitCompraAbreviado'), $params,
                    ['title'=>trans('modelProduct.attributes.valorUnitCompra')]) !!}</th>

                <th>{!! link_to_route_sort_by('products.index', 'estoque', trans('modelProduct.attributes.estoque'), $params) !!}</th>
                <th>{!! link_to_route_sort_by('products.index', 'estoque_minimo', trans('modelProduct.attributes.estoqueMinimo'), $params) !!}</th>
                <th class="col-sm-1">{{ trans('modelProduct.attributes.status') }}</th>
                <th>{{ trans('product.actionTitle') }}</th>
            </tr>
        </thead>
        <tbody>
            {!! Form::model($product, [
            'method'=>$method,
            'url'=>route($route, isset($product->id)?[$product->id]+$params:$params),
            'files' => true,
            ]) !!}
            <!-- method Form Input -->
            {!! Form::hidden('method',$method) !!}
                <tr>
                    <td></td>
                    <td>{!! Form::input('number', 'cod_barra', null, ['maxlength'=>13, 'min'=>'10000000', 'max'=>'9999999999999', 'class'=>'form-control numbersOnly nextFocusEnter']) !!}</td>
                    <td>{!! Form::input('number', 'cod_fiscal', null, ['maxlength'=>8, 'min'=>'01012100', 'max'=>'97060000', 'class'=>'form-control numbersOnly']) !!}</td>
                    <td>{!! Form::text('nome', null, ['class'=>'form-control', 'required']) !!}</td>
                    <td>{!! Form::file('imagem', ['class'=>'form-control', 'accept'=>'image/*']) !!}</td>
                    <td>{!! Form::checkbox('promocao', 1, null, ['class'=>'']) !!}</td>
                    <td>{!! Form::select('grupos[]', $grupos, $group_selected, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
                    <td>{!! Form::select('cost_id', $costs, $cost_selected, ['class'=>'form-control select2']) !!}</td>
                    <td>{!! Form::text('valorUnitVenda', null, ['size'=>'8', 'class'=>'form-control']) !!}</td>
                    <td>{!! Form::text('valorUnitVendaPromocao', null, ['size'=>'8', 'class'=>'form-control']) !!}</td>
                    <td>{!! Form::text('valorUnitCompra', null, ['size'=>'10', 'class'=>'form-control']) !!}</td>


                    <td>{!! Form::checkbox('estoque', 1, null, ['class'=>'']) !!}</td>
                    <td>{!! Form::text('estoque_minimo', null, ['size'=>'8', 'class'=>'form-control']) !!}</td>
                    <td>{!! Form::select('status[]', $status, $status_selected, ['class'=>'form-control select2tag', 'multiple']) !!}</td>
                    <td>{!! Form::submit($submitButtonText, ['class'=>'form-control btn btn-primary']) !!}</td>
                </tr>
            {!! Form::close() !!}

            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->cod_barra }}</td>
                    <td>{{ $product->cod_fiscal }}</td>
                    <td>{{ $product->nome }}</td>
                    <td>{{ $product->imagem }}</td>
                    <td>{{ trans('modelProduct.values.promocao.'.$product->promocao) }}</td>
                    <td>{{ $product->group_list }}</td>
                    <td>{{ $product->cost?$product->cost->descricao:'-' }}</td>
                    <td>{{ formatBRL($product->valorUnitVenda) }}</td>
                    <td>{{ formatBRL($product->valorUnitVendaPromocao) }}</td>
                    <td>{{ formatBRL($product->valorUnitCompra) }}</td>

                    @if(isset($estoque[$product->id])&&($product->estoque_minimo>0)&&($estoque[$product->id]<$product->estoque_minimo))
                        <td style="color: red">
                    @else
                        <td>
                            @endif
                            {{ isset($estoque[$product->id])?$estoque[$product->id]:trans('modelProduct.attributes.semEstoque') }}
                        </td>
                        <td>{{ $product->estoque_minimo }}</td>
                        <td>{{ $product->status_list }}</td>
                    <td>
                        <div style="width: 90px">
                            {!! Form::open([
                            'url'=>route('products.destroy', [$host,$product->id]),
                            'id' => 'form'.$product->id,
                            'method' => 'DELETE',
                            ]) !!}

                            {!! sprintf( link_to_route('products.edit', '%s', [$product->id]+$params, [
                            'title'=>trans('product.actionEditTitle'),
                            ]), '<span style="margin: 0px 10px 0px 0px" class="glyphicon glyphicon-pencil btn btn-default btn-sm"></span>' ) !!}

                            {{--{!! link_to('#','<span class="glyphicon glyphicon-remove"></span>', ['title'=>trans('product.actionDeleteTitle')]) !!}--}}
                            {!! sprintf( link_to('#', '%s', [
                            'title'=>trans('product.actionDeleteTitle'),
                            'send-delete'=>$product->id,
                            ]), '<span class="glyphicon glyphicon-remove btn btn-default btn-sm"></span>' ) !!}
                            {{--{!! sprintf( link_to_route('products.destroy', '%s', [$host,$product->id], ['title'=>trans('product.actionDeleteTitle')]), '<span class="glyphicon glyphicon-remove"></span>' ) !!}--}}

                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center"><em>{{ trans('product.empty') }}</em></td>
                </tr>
            @endforelse
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
    @include('angular.numbersOnlyDirective')
    @include('angular.nextFocusEnterDirective')
@endsection