<div style="padding: 19px;margin-bottom: 20px;
    background-color: #f5f5f5;border: 1px solid #e3e3e3;border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);">
    <h1>Nova Ordem nº {!! link_to_route('orders.edit',$order->id,[$host,$order->id]) !!}</h1>
    <p>Entregar em: {{ $order->address->endereco }}</p>
    <p>Tipo: {{ $order->type->descricao }}</p>
    <p>Pagamento: {{ $order->payment->descricao }}</p>
    <p>Troco: {{ $order->troco }}</p>
    <p>Moeda: {{ $order->currency->nome_universal }} - {{ $order->currency->descricao }}</p>
    <p>Status: {{ $order->status_list }}</p>
    @foreach($order->orderItems as $item)
        <p>- {{ $item->quantidade }} x {{ formatBRL($item->valor_unitario) }}: {{ $item->product->nome }} = {{ formatBRL($item->quantidade*$item->valor_unitario) }}</p>
    @endforeach
    <p>Valor total: {{ formatBRL($order->valor_total) }}</p>
</div>
<div style="padding: 19px;margin-bottom: 20px;
    background-color: #f5f5f5;border: 1px solid #e3e3e3;border-radius: 4px;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);">
    @include('emails.partner.userInfo')
</div>