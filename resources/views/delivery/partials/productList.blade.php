{!! Form::model($products, ['url'=>secure_route('delivery.addCart', $host), 'id'=>'form-add-setting']) !!}

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.cervejasTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($cervejas as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

@if(count($lanches))
    <div class="panel panel-default">
        <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.lanchesTitle') }}</h2></div>
        <div class="panel-body">
            @foreach($lanches as $product)
                <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                    @include('delivery.partials.productBlock')
                    <div class="row" style="margin: 0px;">
                        @include('delivery.partials.productBlockForm')
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif


<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.vinhosTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($vinhos as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.tabacariaTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($tabacaria as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.destiladosTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($destilados as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.energeticosTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($energeticos as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

@if(count($sucos))
    <div class="panel panel-default">
        <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.sucosTitle') }}</h2></div>
        <div class="panel-body">
            @foreach($sucos as $product)
                <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                    @include('delivery.partials.productBlock')
                    <div class="row" style="margin: 0px;">
                        @include('delivery.partials.productBlockForm')
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif


<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.refrigerantesTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($refrigerantes as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.outrosTitle') }}</h2></div>
    <div class="panel-body">
        @foreach($outros as $product)
            <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><h2 class="h2s text-left">{{ trans('delivery.categorias.porcoesTitle') }}</h2></div>
    <div class="panel-body">
        @if(count($porcoes))
            @foreach($porcoes as $product)
                <div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">
                    @include('delivery.partials.productBlock')
                    <div class="row" style="margin: 0px;">
                        @include('delivery.partials.productBlockForm')
                    </div>
                </div>
            @endforeach
        @else
            <em>{{ trans('delivery.categorias.itensEmpty') }}</em>
        @endif

    </div>
</div>

{{--<div class="" style="list-style-type: none; padding: 0px;">--}}
    {{--@foreach($products as $product)--}}
        {{--@if( array_search_second_level($product->status->toArray(),'status','ativado') && isset($estoque[$product->id])&&($estoque[$product->id]>0) )--}}
            {{--<div class="col-xs-2 col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px 0px;">--}}
                {{--@include('delivery.partials.productBlock')--}}
                {{--<div class="row" style="margin: 0px;">--}}
                    {{--@include('delivery.partials.productBlockForm')--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--@endforeach--}}
{{--</div>--}}
{!! Form::close() !!}