<?php namespace App\Repositories;

use App\Models\CostAllocate;
use App\Models\Order;
use DebugBar\DebugBar;

class ReportRepository {
    public function preparaReceitaVendas(&$orders){
//        dd($orders->id);
//        if($orders->id==4)
        foreach($orders as $key => $order){
            if ($this->isOrdemVenda($order) && $this->isOrdemAVista($order)){
                $diario[$order->id.'receita'] = $this->registraReceitaVendas($order);
                $diario[$order->id.'estoque'] = $this->registraBaixaEstoque($order);
            }
        }
//        dd($diario);
        return $diario;
    }

    private function isOrdemVenda($order)
    {
        return ($order->type->tipo=='ordemVenda');
    }

    private function isOrdemAVista($order)
    {
        return ($order->payment->pagamento=='vistad');
    }

    private function getContaDebitoCaixa()
    {
        return CostAllocate::where(['nome'=>'caixa'])->first();
    }

    private function getContaCreditoVendas()
    {
        return CostAllocate::where(['nome'=>'receitaVendasMercadorias'])->first();
    }

    private function getContaDebitoCustoMercadorias()
    {
        return CostAllocate::where(['nome'=>'custoMercadoriasVendidas'])->first();
    }

    private function getContaCreditoEstoque()
    {
        return CostAllocate::where(['nome'=>'estoqueMercadorias'])->first();
    }

    /**
     * @param $order
     * @return array
     */
    private function registraReceitaVendas(&$order)
    {
        // receita de vendas
//        $diario['posted_at'] = $order->posted_at;
//        $diario['posted_at'] = $order->posted_at_carbon;
        $diario['posted_at'] = $order->posted_at_data;
        $diario['transacao']='Receita de vendas';
        $diario['order'] = $order;
        foreach ($order->orderItems as $item) {
            $diario['items'][$item->id]['descricao'] = $item->product->nome;
            $diario['items'][$item->id]['valor'] = $item->valor_unitario * $item->quantidade;
            $diario['items'][$item->id]['debito'] = $item->cost;//$this->getContaDebitoCaixa();
            $diario['items'][$item->id]['credito'] = $item->cost;//$this->getContaCreditoVendas();
//            $diario['items'][$item->id]['credito']=$item->cost;
        }
        return $diario;
    }

    /**
     * @param $order
     * @return array
     */
    private function registraBaixaEstoque(&$order)
    {
        // baixar a mercadoria do estoque
//        $diario['posted_at']=$order->posted_at;
//        $diario['posted_at'] = $order->posted_at_carbon;
        $diario['posted_at'] =  $order->posted_at_data;
        $diario['transacao']='Baixa de estoque';
        $diario['order']=$order;
        foreach ($order->orderItems as $item) {
            $diario['items'][$item->id]['descricao'] = $item->product->nome;
            $diario['items'][$item->id]['valor']=$item->valor_unitario*$item->quantidade;
            $diario['items'][$item->id]['debito']=$item->cost;//$this->getContaDebitoCustoMercadorias();
            $diario['items'][$item->id]['credito']=$item->cost;//$this->getContaCreditoEstoque();
//            $diario['items'][$item->id]['credito']=$item->cost;
        }
        return $diario;
    }
}