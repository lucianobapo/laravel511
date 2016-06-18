<ul class="nav nav-pills nav-media-stacked" style=" margin-bottom: 10px">
    <li class="{{ Route::currentRouteName()=='orders.create'?'active':'' }}">
        {!! link_to_route('orders.create', trans('order.menu.create'), isset($host)?$host:null, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.index'?'active':'' }}">
        {!! link_to_route('orders.index', trans('order.menu.allOrder'), isset($host)?$host:null, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.abertas'?'active':'' }}">
        {!! link_to_route('orders.abertas', trans('order.menu.abertas'), isset($host)?$host:null, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.compras'?'active':'' }}">
        {!! link_to_route('orders.compras', trans('order.menu.compras'), isset($host)?$host:null) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.vendas'?'active':'' }}">
        {!! link_to_route('orders.vendas', trans('order.menu.vendas'), isset($host)?$host:null, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
</ul>