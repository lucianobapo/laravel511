<ul class="nav nav-pills nav-media-stacked" style=" margin-bottom: 10px">
    <li class="{{ Route::currentRouteName()=='orders.create'?'active':'' }}">
        {!! link_to_route('orders.create', trans('order.menu.create'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='confirmations.index'?'active':'' }}">
        {!! link_to_route('confirmations.index', trans('order.menu.confirmation'), $host, [
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
    <li class="{{ Route::currentRouteName()=='ordersSearch.compras'?'active':'' }}">
        {!! link_to_route('ordersSearch.compras', trans('order.menu.compras'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
    <li class="{{ Route::currentRouteName()=='ordersSearch.vendas'?'active':'' }}">
        {!! link_to_route('ordersSearch.vendas', trans('order.menu.vendas'), $host, [
        'class'=>'',
        'style' => ''
        ]) !!}
    </li>
</ul>