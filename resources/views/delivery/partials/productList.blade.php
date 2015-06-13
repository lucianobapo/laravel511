{!! Form::model($products, ['url'=>route('delivery.addCart', $host), 'id'=>'form-add-setting']) !!}
<div style="list-style-type: none; padding: 0px;">
    @foreach($products as $product)
        @if(isset($estoque[$product->id])&&($estoque[$product->id]>0)&&array_search_second_level($product->status->toArray(),'status','ativado'))
            <div class="col-sm-2 well" style="min-width: 200px; float: none; display: inline-block; vertical-align: top; padding: 0px; margin: 0px 5px 10px;">
                @include('delivery.partials.productBlock')
                <div class="row" style="margin: 0px;">
                    @include('delivery.partials.productBlockForm')
                </div>
            </div>
        @endif
    @endforeach
</div>
{!! Form::close() !!}