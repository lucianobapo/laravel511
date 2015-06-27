<?php namespace App\Repositories;

use App\Models\ItemOrder;
use App\Models\Product;
use App\Models\SharedOrderType;
use Carbon\Carbon;

class OrderRepository {
    public function calculaEstoque()
    {
        $saldo_produtos = [];
        $saldo_produtos['custoTotal'] = 0;
        $saldo_produtos['valorVendaTotal'] = 0;
        $products = Product::with('itemOrders','itemOrders.order','itemOrders.order.type','itemOrders.order.status','status','groups')->get();
        foreach ($products as $product) {
            if (!$product->estoque) continue;
            if ($product->checkStatus($product->status->toArray(),'Desativado')) continue;
            if ($product->checkGroup($product->groups->toArray(),'Estoque Produção 3')) {

                $saldo_produtos['estoque'][$product->id]=3;
                continue;
            }
            if ($product->checkGroup($product->groups->toArray(),'Estoque Revenda 8-18')) {
                if ( (Carbon::now()->hour>=8)&&(Carbon::now()->hour<=18) )
                    $saldo_produtos['estoque'][$product->id]=2;
            }
            //12642845000160
            $custo = 0;
            $index = 0;
            foreach ($product->itemOrders as $item) {
                if (is_null($ord = $item->order)) continue;
                if (strpos($ord->status_list,'Finalizado')===false) continue;

                if ($item->order->type->tipo=='ordemVenda')
                    $quantidade=-$item->quantidade;
                elseif ($item->order->type->tipo=='ordemCompra'){
                    $quantidade=+$item->quantidade;
                    $custo = $custo+$item->valor_unitario;
                    $index = $index + 1;
                }


                if (isset($saldo_produtos['estoque'][$item->product_id]))
                    $saldo_produtos['estoque'][$item->product_id] = $saldo_produtos['estoque'][$item->product_id]+$quantidade;
                else
                    $saldo_produtos['estoque'][$item->product_id] = $quantidade;
            }

            if (isset($saldo_produtos['estoque'][$product->id])){
                $saldo_produtos['valorVenda'][$product->id]=$saldo_produtos['estoque'][$product->id]*($product->promocao?$product->valorUnitVendaPromocao:$product->valorUnitVenda);
                $saldo_produtos['valorVendaTotal']=$saldo_produtos['valorVendaTotal']+$saldo_produtos['valorVenda'][$product->id];
                $saldo_produtos['custoMedio'][$product->id]=$custo>0?$custo/$index:0;
                $saldo_produtos['custoMedioSubTotal'][$product->id]=$saldo_produtos['estoque'][$product->id]>0?$saldo_produtos['custoMedio'][$product->id]*$saldo_produtos['estoque'][$product->id]:0;
                $saldo_produtos['custoTotal'] = $saldo_produtos['custoTotal'] + $saldo_produtos['custoMedioSubTotal'][$product->id];
            }
        }
        return $saldo_produtos;
    }
}