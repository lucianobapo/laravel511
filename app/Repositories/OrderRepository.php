<?php namespace App\Repositories;

use App\Models\Product;
use App\Models\SharedOrderType;
use Carbon\Carbon;

class OrderRepository {
    public function calculaEstoque()
    {
        $saldo_produtos = [];
        $products = Product::with('itemOrders','itemOrders.order','itemOrders.order.type','itemOrders.order.status','status','groups')->get();
        foreach ($products as $product) {
            if (!$product->estoque) continue;
            if ($product->checkStatus($product->status->toArray(),'Desativado')) continue;
            if ($product->checkGroup($product->groups->toArray(),'Estoque Produção 3')) {
                $saldo_produtos[$product->id]=3;
                continue;
            }
            if ($product->checkGroup($product->groups->toArray(),'Estoque Revenda 8-18')) {
                if ( (Carbon::now()->hour>=8)&&(Carbon::now()->hour<=18) )
                    $saldo_produtos[$product->id]=2;
            }

            foreach ($product->itemOrders as $item) {
                if (is_null($ord = $item->order)) continue;
                if (strpos($ord->status_list,'Finalizado')===false) continue;

                if ($item->order->type->tipo=='ordemVenda')
                    $quantidade=-$item->quantidade;
                elseif ($item->order->type->tipo=='ordemCompra')
                    $quantidade=+$item->quantidade;

                if (isset($saldo_produtos[$item->product_id]))
                    $saldo_produtos[$item->product_id] = $saldo_produtos[$item->product_id]+$quantidade;
                else
                    $saldo_produtos[$item->product_id] = $quantidade;
            }
        }
        return $saldo_produtos;
    }
}