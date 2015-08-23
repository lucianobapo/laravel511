<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 22/08/15
 * Time: 13:13
 */

namespace App\Repositories;

use App\Models\Product;
use Carbon\Carbon;

class ProductRepository {

    /**
     * @var Product $product
     */
    private $product;
//    private $orderRepository;
    public $estoque;

    /**
     * @param Product $product
     */
    public function __construct(Product $product, OrderRepository $orderRepository) {
        $this->product = $product;
//        $this->orderRepository = $orderRepository;
        $this->estoque = $orderRepository->calculaEstoque()['estoque'];
    }

    public function calculaEstoque()
    {
        $saldo_produtos['estoque'] = [];
        $saldo_produtos['custoMedio'] = [];
        $saldo_produtos['custoMedioSubTotal'] = [];
        $saldo_produtos['valorVenda'] = [];
        $saldo_produtos['custoTotal'] = 0;
        $saldo_produtos['valorVendaTotal'] = 0;
//        $products = Product::with('itemOrders','itemOrders.order','itemOrders.order.type','itemOrders.order.status','status','groups')->get();
        foreach ($this->getProductsDelivery() as $product) {
            if (!$product->estoque) continue;
            if ($product->checkStatus($product->status->toArray(),'desativado')) {
                continue;
//                dd($product->nome);//
            }
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

    /**
     * @return Product
     */
    public function getProductsCardapio() {
        return $this->product
            ->with('status','groups')
            ->orderBy('promocao', 'desc' )
            ->orderBy('nome', 'asc' )
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Ativado')!==false)
                    return $item;
            })
            ->filter(function($item) {
                if (strpos($item->group_list,'Delivery')!==false)
                    return $item;
            });
    }

    public function getProductsDelivery() {
        $aux = $this->getProductsCardapio()
            ->filter(function($item) {
                if ($this->estoque[$item->id]>0)
                    return $item;
            });
        return $aux;
    }

    public function getProductsPorcoes() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Porções')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsCervejas() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Cervejas')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsVinhos() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Vinhos')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsDestilados() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Destilados')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsRefrigerantes() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Refrigerantes')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsEnergeticos() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Energéticos')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsTabacaria() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Tabacaria')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsSucos() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Sucos')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }

    public function getProductsOutros() {
        $aux = $this->getProductsDelivery()
            ->filter(function($item) {
                if ($item->categoria_list=='Outros')
                    return $item;
            });
//        dd($aux);
        return $aux;
    }
}