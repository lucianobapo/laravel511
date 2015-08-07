<div style="padding: 19px;margin-bottom: 20px;
    background-color: #f5f5f5;border: 1px solid #e3e3e3;border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);">
    {!! link_to_delivery_logo('http://delivery.ilhanet.com'.'/img/logo-delivery2.png', ['delivery']) !!}

    <h1>Seu pedido nº {{ $order->id }} foi confirmado.</h1>
    @if(isset($user))
        <p><img src="{{ $user->avatar }}" style="
padding: 4px;
line-height: 1.42857143;
background-color: #ffffff;
border: 1px solid #dddddd;
border-radius: 4px;
transition: all 0.2s ease-in-out;
display: inline-block;
max-width: 100%;
height: auto;"></p>
        <h3><strong>{{ $user->name }}</strong></h3>
    @else
        <h3><strong>{{ $partner->nome }}</strong></h3>
    @endif
    <h3>{{ $msg }}</h3>
    <p>Entregar em: {{ $order->address->endereco }}</p>
    <p>Observação: {{ $order->address->obs }}</p>
    <p>Pagamento: {{ $order->payment->descricao }}</p>
    @if(empty($order->troco))
        <p>Troco: <em>Sem necessidade de Troco</em></p>
    @else
        <p>Troco: {{ $order->troco }}</p>
    @endif

    @foreach($order->orderItems as $item)
        <p>- {{ $item->quantidade }} x {{ formatBRL($item->valor_unitario) }}: {{ $item->product->nome }} = {{ formatBRL($item->quantidade*$item->valor_unitario) }}</p>
    @endforeach
    <p><strong>Valor total: {{ formatBRL($order->valor_total) }}</strong></p>
</div>