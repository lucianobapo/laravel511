<ul class="nav nav-pills nav-media-stacked" style=" margin-bottom: 10px">
    <li class="{{ Route::currentRouteName()=='orders.create'?'active':'' }}">
        {!! link_to_route('orders.create', trans('order.menu.create'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.index'?'active':'' }}">
        {!! link_to_route('orders.index', trans('order.menu.allOrder'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.abertas'?'active':'' }}">
        {!! link_to_route('orders.abertas', trans('order.menu.abertas'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.compras'?'active':'' }}">
        {!! link_to_route('orders.compras', trans('order.menu.compras'), $host) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='orders.vendas'?'active':'' }}">
        {!! link_to_route('orders.vendas', trans('order.menu.vendas'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
</ul>