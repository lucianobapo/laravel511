<?php namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderRepository {

    /**
     * @var Order $order
     */
    private $order;
    public $ordersGetWithTypeStatusConfirmations = null;
    public $ordersGetWithTypeStatusPaymentOrderItemsCost = null;
    private $estoque;

    /**
     * @param Order $order
     */
    public function __construct(Order $order) {
        $this->order = $order;
        $params['sortBy'] = ['posted_at','id'];
        $this->ordersGetWithTypeStatusConfirmations = $this->getOrdersBaseWhereSorted([], ['type','status','confirmations'], $params)->get();
        $this->ordersGetWithTypeStatusPaymentOrderItemsCost = $this->getOrdersBaseWhereSorted([], ['type','status','payment','orderItems','orderItems.cost'], $params)->get();
        $this->estoque = $this->calculaEstoque();
    }

    public function calculaEstoque()
    {
        $saldo_produtos['estoque'] = [];
        $saldo_produtos['custoMedio'] = [];
        $saldo_produtos['custoMedioSubTotal'] = [];
        $saldo_produtos['valorVenda'] = [];
        $saldo_produtos['custoTotal'] = 0;
        $saldo_produtos['valorVendaTotal'] = 0;
        $products = Product::with('itemOrders','itemOrders.order','itemOrders.order.type','itemOrders.order.status','status','groups')->get();
        foreach ($products as $product) {
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
     * Sort Models
     *
     * @param Model $model
     * @param $params
     * @return array
     */
    public function sorting(Model $model, &$params, $defaultColumn='id', $defaultDirection=false)
    {
        if (!isset($params['direction'])) $params['direction'] = $defaultDirection;
        if (!isset($params['sortBy'])) $params['sortBy'] = $defaultColumn;

        return $model->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
    }

    public function getProductsDelivery()
    {
//        $produtos = ProductGroup::where(['grupo'=>'Delivery'])
//            ->orderBy('grupo', 'desc' )
//            ->first()
//            ->products()
//            ->with('status')
//            ->get()
//            ->filter(function($item) {
//                if (strpos($item->status_list,'Ativado')!==false)
//                    return $item;
//            });


        $produtos2 = Product::with('status','groups')
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
//        dd($produtos2->toArray());
        return $produtos2;
    }


//    /**
//     * @return Order
//     */
//    private function getOrdersBase($withs=[]) {
//        return $this->order
//            ->with($withs);
//    }

    /**
     * @return Order
     */
    private function getOrdersBaseWhereSorted($wheres=[], $withs=[], &$params=[], $defaultColumn='id', $defaultDirection=false) {

        if (!isset($params['direction'])) $params['direction'] = $defaultDirection;
        if (!isset($params['sortBy'])) $params['sortBy'] = $defaultColumn;

        $result = $this->order
            ->with($withs)
            ->where($wheres);

        if (is_array($params['sortBy'])) {
            foreach ($params['sortBy'] as $sort) {
                $result = $result->orderBy($sort, ($params['direction'] ? 'asc' : 'desc'));
            }
        } else {
            $result = $result->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
        }

        return $result;
    }

    /**
     * @return Order
     */
    public function getOrdersWhereSortedPaginated($wheres=[], $withs=[], &$params=[], $defaultColumn='id', $defaultDirection=false) {
        return $this->getOrdersBaseWhereSorted($wheres, $withs, $params, $defaultColumn, $defaultDirection)
            ->paginate(config('delivery.orderListCountMax'))
            ->appends($params);
    }

    /**
     * @return Order
     */
    public function getOrdersSortedPaginated($withs=[], &$params=[], $defaultColumn='id', $defaultDirection=false) {
        return $this->getOrdersBaseWhereSorted([], $withs, $params, $defaultColumn, $defaultDirection)
            ->paginate(config('delivery.orderListCountMax'))
            ->appends($params);
    }

    /**
     * @return Order
     */
    public function getOrdersFinished($withs = null) {
        if ($withs===null) {
            return $this->ordersGetWithTypeStatusConfirmations
                ->filter(function($item) {
                    if (strpos($item->status_list,'Finalizado')!==false)
                        return $item;
                });
        } else {
            return $withs
                ->filter(function($item) {
                    if (strpos($item->status_list,'Finalizado')!==false)
                        return $item;
                });
        }

    }

    /**
     * @return Order
     */
    public function getOrdersOpened() {
        return $this->ordersGetWithTypeStatusConfirmations
            ->filter(function($item) {
                if (strpos($item->status_list,'Aberto')!==false)
                    return $item;
            });
    }

    /**
     * @return Order
     */
    public function getOrdersCanceled() {
        return $this->ordersGetWithTypeStatusConfirmations
            ->filter(function($item) {
                if (strpos($item->status_list,'Cancelado')!==false)
                    return $item;
            });
    }


    /**
     * @return Order
     */
    public function getSalesOrdersFinished() {
        return $this->getOrdersFinished()
            ->filter(function($item) {
                if ($item->type->tipo=='ordemVenda')
                    return $item;
            });
    }

    /**
     * @return Order
     */
    public function getPurchaseOrdersFinished() {
        return $this->getOrdersFinished()
            ->filter(function($item) {
                if ($item->type->tipo=='ordemCompra')
                    return $item;
            });
    }


    /**
     * @return Order
     */
    public function getSalesOrdersFinishedDelivered() {
        return $this->getSalesOrdersFinished()
            ->filter(function($item) {
                if ($item->hasConfirmation('entregando')
                    && $item->hasConfirmation('entregue')
                    && ($item->kmFinal>$item->kmInicial)
                ){
                    $this->kmOrdersVendaEntregue[$item->id] = $item->kmFinal-$item->kmInicial;
                    return $item;
                }
            });
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @param array $arrayDaSoma
     */
    public function getSomaMeses(Carbon $from, Carbon $to, array &$arrayDaSoma){
        $ordersMes = $this->getOrdersFinished()
            ->filter(function($item) use ($from, $to) {
                if ( ($item->posted_at_carbon>=$from->toDateTimeString()) && ($item->posted_at_carbon<=$to->toDateTimeString()) )
                    return $item;
            });
        if (count($ordersMes)>0){
            $arrayDaSoma[$from->format('m/Y')] = $this->somaValorOrdensMes($ordersMes);
            return $this->getSomaMeses($from->subMonth(), $to->subMonth(),$arrayDaSoma);
        } else return;
    }

    /**
     * @param $ordersMes
     * @return array
     */
    private function somaValorOrdensMes($ordersMes)
    {
        $data['vendas'] = 0;
        $data['compras'] = 0;
        $data['creditoFinanceiro'] = 0;
        $data['debitoFinanceiro'] = 0;
        foreach ($ordersMes as $orderValue) {
            if ($orderValue->type->tipo == 'ordemVenda') {
                $data['vendas'] = $data['vendas'] + $orderValue->valor_total;
            }
            if ($orderValue->type->tipo == 'ordemCompra') {
                $data['compras'] = $data['compras'] + $orderValue->valor_total;
            }
            if ($orderValue->type->tipo == 'creditoFinanceiro') {
                $data['creditoFinanceiro'] = $data['creditoFinanceiro'] + $orderValue->valor_total;
            }
            if ($orderValue->type->tipo == 'debitoFinanceiro') {
                $data['debitoFinanceiro'] = $data['debitoFinanceiro'] + $orderValue->valor_total;
            }
        }
        return $data;
    }


    public function getComporPeriodos(array &$periodos, Carbon $finish_date, Carbon $start_date=null) {
        $ordersMes = $this->getOrdersFinished($this->ordersGetWithTypeStatusPaymentOrderItemsCost)
            ->filter(function($item) use ($finish_date) {
                if ( ($item->posted_at_carbon>=$finish_date->startOfMonth()->toDateTimeString()) && ($item->posted_at_carbon<=$finish_date->endOfMonth()->toDateTimeString()) )
                    return $item;
            });

        if ( (count($ordersMes)>0) && ($finish_date>$start_date) ){
            $periodos[] = [
                'title' => $finish_date->startOfMonth()->format('m/Y'),
                'ordersMes' => $this->comporDre($ordersMes, $this->estoque),
            ];
            return $this->getComporPeriodos($periodos, $finish_date->subMonth(), $start_date);
        } else return;
    }

    private function comporDre(&$orders, array $estoque) {
        $data = [];
        $ordersFiltred = $orders->filter(function($item) {
            if ($item->type->tipo == 'ordemVenda')
                return $item;
        });
        $data['receitaBrutaDinheiro'] = 0;
        $data['receitaBrutaCartaoCredito'] = 0;
        $data['receitaBrutaCartaoDebito'] = 0;

        $data['consumoMedioEstoque'] = 0;
        foreach ($ordersFiltred as $order) {
            // calcula receita
            if ($order->payment->pagamento=='vistad')
                $data['receitaBrutaDinheiro'] = $data['receitaBrutaDinheiro'] + $order->valor_total;
            if ($order->payment->pagamento=='vistacc')
                $data['receitaBrutaCartaoCredito'] = $data['receitaBrutaCartaoCredito'] + $order->valor_total;
            if ($order->payment->pagamento=='vistacd')
                $data['receitaBrutaCartaoDebito'] = $data['receitaBrutaCartaoDebito'] + $order->valor_total;

            foreach ($order->orderItems as $item) {
                //calcula custo médio
                if ( ($item->cost->nome=='estoqueMercadorias')&&( isset($estoque['custoMedio'][$item->product_id]) ) ) {
                    $data['consumoMedioEstoque'] = $data['consumoMedioEstoque'] + ($estoque['custoMedio'][$item->product_id]*$item->quantidade);
                } else {
//                    \Debugbar::info($item->product->nome);
//                    \Debugbar::info($estoque['custoMedio']);
                }
            }
        }
//        if ($data['custoMedioVendas']==0) dd($ordersFiltred->toArray());
        $data['receitaBruta'] = $data['receitaBrutaDinheiro'] + $data['receitaBrutaCartaoCredito'] + $data['receitaBrutaCartaoDebito'];
        $data['honorariosPaylevenCredito'] = $data['receitaBrutaCartaoCredito']*0.0339;
        $data['honorariosPaylevenDebito'] = $data['receitaBrutaCartaoDebito']*0.0269;
        $data['honorariosPayleven'] = $data['honorariosPaylevenDebito']+$data['honorariosPaylevenCredito'];
        $data['honorariosPedidosJa'] = 0;

        $ordersFiltred = $orders->filter(function($item) {
            if ($item->type->tipo == 'ordemCompra')
                return $item;
        });
        $data['compras'] = 0;
        $data['saldo'] = 0;

        $data['estoqueMercadorias'] = 0;
        $data['estoqueLanches'] = 0;

        $data['comprasMercadorias'] = 0;
        $data['comprasLanches'] = 0;

//        $data['custoMercadorias'] = 0;
//        $data['custoLanches'] = 0;
        $data['despesasGerais'] = 0;
        $data['despesasMensaisFixas'] = 0;
        $data['despesasMarketingPropaganda'] = 0;
        $data['despesasTransporte'] = 0;
        $data['imposto'] = 0;

        foreach ($ordersFiltred as $order) {
            foreach ($order->orderItems as $item) {
                //calcula estoque
                if ($item->cost->nome=='estoqueMercadorias')
                    $data['estoqueMercadorias'] = $data['estoqueMercadorias'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='estoqueLanches')
                    $data['estoqueLanches'] = $data['estoqueLanches'] + ($item->valor_unitario*$item->quantidade);

                //calcula custo
                if ($item->cost->nome=='Mercadorias')
                    $data['comprasMercadorias'] = $data['comprasMercadorias'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Lanches')
                    $data['comprasLanches'] = $data['comprasLanches'] + ($item->valor_unitario*$item->quantidade);

                if ($item->cost->nome=='Despesas')
                    $data['despesasGerais'] = $data['despesasGerais'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='despesasMensaisFixas')
                    $data['despesasMensaisFixas'] = $data['despesasMensaisFixas'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='despesasMarketingPropaganda')
                    $data['despesasMarketingPropaganda'] = $data['despesasMarketingPropaganda'] + ($item->valor_unitario*$item->quantidade);


                if ($item->cost->nome=='Transporte')
                    $data['despesasTransporte'] = $data['despesasTransporte'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Impostos')
                    $data['imposto'] = $data['imposto'] + ($item->valor_unitario*$item->quantidade);

            }
        }

        $data['deducaoReceita'] = $data['honorariosPayleven']+$data['honorariosPedidosJa']+$data['imposto'];
        $data['receitaLiquida'] = $data['receitaBruta']-$data['deducaoReceita'];

//        $data['custoProdutos'] = $data['custoMedioVendas'];
        $data['custoProdutos'] = $data['comprasMercadorias']+$data['comprasLanches']+$data['consumoMedioEstoque'];

        $data['comprasEstoque'] = $data['estoqueMercadorias']+$data['estoqueLanches'];
        $data['saldo'] = $data['comprasEstoque']-$data['consumoMedioEstoque'];

        $data['margem'] = $data['receitaLiquida'] - $data['custoProdutos'];

        $data['despesas'] = $data['despesasGerais'] + $data['despesasMensaisFixas'] + $data['despesasTransporte'];

        $data['ebitda'] = $data['margem'] - $data['despesas'];

        return $data;
    }


    /**
     * @return array
     */
    public function getLevantamentoDeOrdens() {
        function calculaPosicao(array $array){
            arsort($array);
            $i=1;
            foreach ($array as $key => $value) {
                if ($i<=config('delivery.reports.maxPodium')) $resultado[$key] = $i;
                $i++;
            }
            return $resultado;
        }
        foreach($this->getSalesOrdersFinished() as $order){
            $indexMes = $order->posted_at_carbon->format('m');
            $mes[$indexMes] = isset($mes[$indexMes])?$mes[$indexMes]+1:1;
            $indexMesValor = $order->posted_at_carbon->format('m');
            $mesValor[$indexMesValor] = isset($mesValor[$indexMesValor])?$mesValor[$indexMesValor]+$order->valor_total:$order->valor_total;

            $indexDiaMes = $order->posted_at_carbon->format('d');
            $diaMes[$indexDiaMes] = isset($diaMes[$indexDiaMes])?$diaMes[$indexDiaMes]+1:1;
            $indexDiaMesValor = $order->posted_at_carbon->format('d');
            $diaMesValor[$indexDiaMesValor] = isset($diaMesValor[$indexDiaMesValor])?$diaMesValor[$indexDiaMesValor]+$order->valor_total:$order->valor_total;

            $indexSemana = $order->posted_at_carbon->format('w-').ucfirst(formatDateTranslated($order->posted_at_carbon, 'EEEE'));
            $semana[$indexSemana] = isset($semana[$indexSemana])?$semana[$indexSemana]+1:1;
            $indexSemanaValor = $order->posted_at_carbon->format('w-l');
            $semanaValor[$indexSemanaValor] = isset($semanaValor[$indexSemanaValor])?$semanaValor[$indexSemanaValor]+$order->valor_total:$order->valor_total;

            if (($order->posted_at_carbon->format('H:i')!='01:00')&&($order->posted_at_carbon->format('H:i')!='00:00')){
                $indexHora = $order->posted_at_carbon->format('H');
                $hora[$indexHora] = isset($hora[$indexHora])?$hora[$indexHora]+1:1;
                $indexHoraValor = $order->posted_at_carbon->format('H');
                $horaValor[$indexHoraValor] = isset($horaValor[$indexHoraValor])?$horaValor[$indexHoraValor]+$order->valor_total:$order->valor_total;
            }
        }

        ksort($mes);
        ksort($mesValor);

        $diaMesPosicao = calculaPosicao($diaMes);
        ksort($diaMes);
        $diaMesValorPosicao = calculaPosicao($diaMesValor);
        ksort($diaMesValor);

        $semanaPosicao = calculaPosicao($semana);
        ksort($semana);
        $semanaValorPosicao = calculaPosicao($semanaValor);
        ksort($semanaValor);

        ksort($hora);
        $horaPosicao = calculaPosicao($hora);
        ksort($horaValor);
        $horaValorPosicao = calculaPosicao($horaValor);

        return [
            'ordensMes'=>$mes,
            'somaOrdensMes'=>array_sum($mes),
            'ordensMesValor'=>$mesValor,
            'somaOrdensMesValor'=>array_sum($mesValor),

            'ordensDiaDoMes'=>$diaMes,
            'ordensDiaDoMesPosicao'=>$diaMesPosicao,
            'somaOrdensDiaDoMes'=>array_sum($diaMes),
            'ordensDiaDoMesValor'=>$diaMesValor,
            'ordensDiaDoMesValorPosicao'=>$diaMesValorPosicao,
            'somaOrdensDiaDoMesValor'=>array_sum($diaMesValor),

            'ordensSemana'=>$semana,
            'ordensSemanaPosicao'=>$semanaPosicao,
            'somaOrdensSemana'=>array_sum($semana),
            'ordensSemanaValor'=>$semanaValor,
            'ordensSemanaValorPosicao'=>$semanaValorPosicao,
            'somaOrdensSemanaValor'=>array_sum($semanaValor),

            'ordensHora'=>$hora,
            'ordensHoraPosicao'=>$horaPosicao,
            'somaOrdensHora'=>array_sum($hora),
            'ordensHoraValor'=>$horaValor,
            'ordensHoraValorPosicao'=>$horaValorPosicao,
            'somaOrdensHoraValor'=>array_sum($horaValor),
        ];

    }

}