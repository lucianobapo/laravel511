<?php namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\Repository as CacheRepository;

class OrderRepository {

    /**
     * @var Order $order
     */
    private $order;

    /**
     * @var CacheRepository
     */
    private $cache;
    private $cacheMinutes;
    private $ordersCacheKey;

    public $ordersGetWiths;
//    public $estoqueProduct;
//    public $custoMedioProduct;

    /**
     * @param Order $order
     */
    public function __construct(Order $order, CacheRepository $cache) {
        $this->cache = $cache;
        $this->cacheMinutes = config('cache.queryCacheTimeMinutes');
        $this->ordersCacheKey = getTableCacheKey('orders');
        $this->order = $order;
        $params['sortBy'] = ['orders.posted_at','orders.id'];
        $this->ordersGetWiths = $this->getOrdersBaseWhereSorted([], [
            'type',
            'status',
            'confirmations',
            'payment',
            'partner',
            'currency',
            'address',
            'attachments',
            'orderItems',
            'orderItems.product',
            'orderItems.currency',
            'orderItems.cost'
        ], $params);
    }


    public function getCustoMedioProduct(){
        foreach ($this->getPurchaseOrdersFinished() as $order)
            foreach ($order->orderItems as $item) {
                if (isset($custo[$item->product_id]))
                    $custo[$item->product_id]+=($item->valor_unitario*$item->quantidade);
                else
                    $custo[$item->product_id]=($item->valor_unitario*$item->quantidade);

                if (isset($quantidade[$item->product_id]))
                    $quantidade[$item->product_id]+=$item->quantidade;
                else
                    $quantidade[$item->product_id]=$item->quantidade+0;
            }

        foreach ($custo as $id => $valor){
            $custo[$id] = $valor/$quantidade[$id];
        }
        return $custo;
    }

    public function getComprasProduct(){
        foreach ($this->getPurchaseOrdersFinished() as $order)
            foreach ($order->orderItems as $item)
                if (isset($quantidade[$item->product_id]))
                    $quantidade[$item->product_id]+=$item->quantidade;
                else
                    $quantidade[$item->product_id]=$item->quantidade+0;
        return $quantidade;
    }
    public function getVendasProduct(){
        foreach ($this->getSalesOrdersFinished() as $order)
            foreach ($order->orderItems as $item)
                if (isset($quantidade[$item->product_id]))
                    $quantidade[$item->product_id]+=$item->quantidade;
                else
                    $quantidade[$item->product_id]=$item->quantidade+0;
        return $quantidade;
    }

    public function getEstoqueProduct(){
        $vendas = $this->getVendasProduct();
        foreach ($this->getComprasProduct() as $productId => $compras){
            $estoque[$productId]=$compras-(isset($vendas[$productId])?$vendas[$productId]:0);
        }

        $products = (new ProductGroup)->find(1)->products;
        foreach ($products as $product) {
            foreach ($product->status as $status) {
                if ($status->status=='ativado') $estoque[$product->id]=3;
            }
        }
        return $estoque;
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
    public function getOrdersWhereSortedPaginated($wheres=[], &$params=[], $defaultColumn='id', $defaultDirection=false) {
        $this->toBuilder();

        return $this->sortOrders($params, $defaultColumn, $defaultDirection)
            ->where($wheres)
            ->simplePaginate(config('delivery.orderListCountMax'))
            ->appends($params);
    }



    public function getOrdersSortedPaginated(&$params=[], $defaultColumn='id', $defaultDirection=false) {
        return $this->sortOrders($params, $defaultColumn, $defaultDirection)
            ->simplePaginate(config('delivery.orderListCountMax'))
            ->appends($params);
    }

    private function sortOrders(&$params=[], $defaultColumn='id', $defaultDirection=false) {
        $this->toBuilder();

        if (!isset($params['direction'])) $params['direction'] = $defaultDirection;
        if (!isset($params['sortBy'])) $params['sortBy'] = $defaultColumn;
        $this->ordersGetWiths->getQuery()->orders = [];

        if (is_array($params['sortBy'])) {
            foreach ($params['sortBy'] as $sort) {
                $this->ordersGetWiths = $this->ordersGetWiths->orderBy($sort, ($params['direction'] ? 'asc' : 'desc'));
            }
        } else {
            $this->ordersGetWiths = $this->ordersGetWiths->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
        }
        return $this->ordersGetWiths;
    }

    /**
     * @return Order
     */
    public function getOrdersFinished($withs = null) {
        $this->toCollection();

        return $this->ordersGetWiths
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
    }

    public function getBaseOrdersOpened() {
        $this->toBuilder();
        return $this->ordersGetWiths
            ->select('orders.*')
            ->join('order_shared_stat', 'orders.id', '=', 'order_shared_stat.order_id')
            ->join('shared_stats', 'order_shared_stat.shared_stat_id', '=', 'shared_stats.id')
            ->where('shared_stats.status', '=', 'aberto');
    }

    public function getOrdersOpenedSorted(&$params) {
        $this->toBuilder();
        $this->ordersGetWiths = $this->getBaseOrdersOpened();
        return $this->sortOrders($params)
            ->get();
    }

    public function getOrdersOpened() {
        $this->toCollection();
        return $this->ordersGetWiths
            ->filter(function($item) {
                if (strpos($item->status_list,'Aberto')!==false)
                    return $item;
            });
    }

    /**
     * @return Order
     */
    public function getOrdersCanceled() {
        $this->toCollection();
        return $this->ordersGetWiths
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
    private function getSomaMeses(Carbon $from, Carbon $to, array &$arrayDaSoma){
        $ordersMes = $this->getOrdersFinished()
            ->filter(function($item) use ($from, $to) {
                if ( ($item->posted_at_carbon>=$from->toDateTimeString()) && ($item->posted_at_carbon<=$to->toDateTimeString()) )
                    return $item;
            });

        if (count($ordersMes)>0){
            $arrayDaSoma[$from->format('m/Y')] = $this->somaValorOrdensMes($ordersMes);
//            \Debugbar::info($from->format('m/Y').' - '.count($ordersMes).' - '.$arrayDaSoma[$from->format('m/Y')]['vendas'].' - '.$arrayDaSoma[$from->format('m/Y')]['compras']);
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
//                \Debugbar::info(count($ordersMes));
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


    private function getComporPeriodos(array &$periodos, Carbon $finish_date, Carbon $start_date=null) {
        $ordersMes = $this->getOrdersFinished()
            ->filter(function($item) use ($finish_date) {
                if ( ($item->posted_at_carbon>=$finish_date->startOfMonth()->toDateTimeString()) && ($item->posted_at_carbon<=$finish_date->endOfMonth()->toDateTimeString()) )
                    return $item;
            });

        if ( (count($ordersMes)>0) && ($finish_date>$start_date) ){
            $periodos[] = [
                'title' => $finish_date->startOfMonth()->format('m/Y'),
                'ordersMes' => $this->comporDre($ordersMes),
            ];
            return $this->getComporPeriodos($periodos, $finish_date->subMonth(), $start_date);
        } else return;
    }

    private function comporDre(&$orders) {
        $data = [];
        $ordersFiltred = $orders->filter(function($item) {
            if ($item->type->tipo == 'ordemVenda')
                return $item;
        });
        $data['receitaBrutaDinheiro'] = 0;
        $data['receitaBrutaCartaoCredito'] = 0;
        $data['receitaBrutaCartaoDebito'] = 0;
        $data['consumoMedioEstoque'] = 0;

        $custoMedio = $this->getCustoMedioProduct();
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
                if ( ($item->cost->nome=='estoqueMercadorias')&&(isset($custoMedio[$item->product_id])) ) {
                    $data['consumoMedioEstoque'] = $data['consumoMedioEstoque'] + ($custoMedio[$item->product_id]*$item->quantidade);
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
        $data['estoqueCigarros'] = 0;
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
                if ($item->cost->nome=='estoqueCigarros')
                    $data['estoqueCigarros'] = $data['estoqueCigarros'] + ($item->valor_unitario*$item->quantidade);
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

        $data['comprasEstoque'] = $data['estoqueMercadorias']+$data['estoqueCigarros']+$data['estoqueLanches'];
        $data['saldo'] = $data['comprasEstoque']-$data['consumoMedioEstoque'];

        $data['margem'] = $data['receitaLiquida'] - $data['custoProdutos'];

        $data['despesas'] = $data['despesasGerais'] + $data['despesasMensaisFixas'] + $data['despesasTransporte'];

        $data['ebitda'] = $data['margem'] - $data['despesas'];

        return $data;
    }


    /**
     * @return array
     */
    private function getLevantamentoDeOrdens() {
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

    private function toCollection() {
        if (get_class($this->ordersGetWiths)=='Illuminate\Database\Eloquent\Builder') {
            $this->ordersGetWiths = $this->ordersGetWiths->get();
        }
    }

    private function toBuilder() {
        if (get_class($this->ordersGetWiths)!='Illuminate\Database\Eloquent\Builder')
            dd('erro no $this->ordersGetWiths');
    }

    /**
     * @return array
     */
    private function getOrdersCount() {
        $this->toCollection();
        return [
            'totalOrder'=>count($this->ordersGetWiths),
            'openedOrders'=>count($this->getOrdersOpened()),
            'cancelledOrders'=>count($this->getOrdersCanceled()),
            'finishedOrders'=>count($this->getOrdersFinished()),
            'totalVenda'=>count($this->getSalesOrdersFinished()),
            'totalCompra'=>count($this->getPurchaseOrdersFinished()),
            'totalVendaEntregue'=>count($this->getSalesOrdersFinishedDelivered()),
        ];
    }

    /**
     * @return array
     */
    private function getOrdersPercentage() {
        $this->toCollection();
        if (($quocienteOrders = count($this->ordersGetWiths))==0) $quocienteOrders = 1;
        return [
            'totalOrder'=>formatPercent(count($this->ordersGetWiths)/$quocienteOrders),
            'openedOrders'=>formatPercent(count($this->getOrdersOpened())/$quocienteOrders),
            'cancelledOrders'=>formatPercent(count($this->getOrdersCanceled())/$quocienteOrders),
            'finishedOrders'=>formatPercent(count($this->getOrdersFinished())/$quocienteOrders),
            'totalVenda'=>formatPercent(count($this->getSalesOrdersFinished())/$quocienteOrders),
            'totalCompra'=>formatPercent(count($this->getPurchaseOrdersFinished())/$quocienteOrders),
            'totalVendaEntregue'=>formatPercent(count($this->getSalesOrdersFinishedDelivered())/$quocienteOrders),
        ];
    }




    /*
     * Serving methods
     * ********************************************
     */


    /**
     * @return array
     */
    public function getCachedEstoque()
    {
        return $this->getCached('ordersCacheKey', 'getEstoqueProduct');
//        $tag = 'estoque';
//        if ($this->cache->tags($tag)->has($this->ordersCacheKey)) {
//        if ($this->cache->has($this->ordersCacheKey)) {
//            return $this->cache->get($this->ordersCacheKey);
////            return $this->getEstoqueProduct();
//        } else {
//            $cacheContent = $this->getEstoqueProduct();
////            $this->cache->tags($tag)->flush();
////            $this->cache->forget($this->ordersCacheKey);
////            $this->cache->tags($tag)->forever($this->ordersCacheKey, $cacheContent);
//            $this->cache->put($this->ordersCacheKey, $cacheContent, $this->cacheMinutes);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedProductCost()
    {
        return $this->getCached('ordersCacheKey', 'getCustoMedioProduct');
//        $tag = 'ProductCost';
//        if ($this->cache->has($this->ordersCacheKey)) {
//            return $this->cache->get($this->ordersCacheKey);
//        } else {
//            $cacheContent = $this->getCustoMedioProduct();
//            $this->cache->put($this->ordersCacheKey,$cacheContent, $this->cacheMinutes);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedProductSales()
    {
//        $tag = 'ProductSales';
        return $this->getCached('ordersCacheKey', 'getVendasProduct');

    }

    /**
     * @return array
     */
    public function getCachedProductPurchase()
    {
//        $tag = 'ProductPurchase';
        return $this->getCached('ordersCacheKey', 'getComprasProduct');

//        if ($this->cache->tags($tag)->has($this->ordersCacheKey)) {
//            return $this->cache->tags($tag)->get($this->ordersCacheKey);
//        } else {
//            $cacheContent = $this->getComprasProduct();
//            $this->cache->tags($tag)->flush();
//            $this->cache->tags($tag)->forever($this->ordersCacheKey,$cacheContent);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedOrdersStatistics() {
        $tag = 'OrdersStatistics';
        if ($this->cache->has($this->ordersCacheKey.$tag)) {
            return $this->cache->get($this->ordersCacheKey.$tag);
        } else {
            $cacheContent = [];
            $this->getSomaMeses(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), $cacheContent);
            $this->cache->put($this->ordersCacheKey.$tag,$cacheContent, $this->cacheMinutes);
            return $cacheContent;
        }
    }

    /**
     * @return array
     */
    public function getCachedFinishedOrdersStatistics() {
        return $this->getCached('ordersCacheKey', 'getLevantamentoDeOrdens');

//        $tag = 'FinishedOrdersStatistics';
//        if ($this->cache->tags($tag)->has($this->ordersCacheKey)) {
//            return $this->cache->tags($tag)->get($this->ordersCacheKey);
//        } else {
//            $cacheContent = $this->getLevantamentoDeOrdens();
//
//            $this->cache->tags($tag)->flush();
//            $this->cache->tags($tag)->forever($this->ordersCacheKey,$cacheContent);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedOrdersCount() {
        return $this->getCached('ordersCacheKey', 'getOrdersCount');
//        $tag = 'OrdersCount';
//        if ($this->cache->tags($tag)->has($this->ordersCacheKey)) {
//            return $this->cache->tags($tag)->get($this->ordersCacheKey);
//        } else {
//            $cacheContent = $this->getOrdersCount();
//
//            $this->cache->tags($tag)->flush();
//            $this->cache->tags($tag)->forever($this->ordersCacheKey,$cacheContent);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedOrdersPercentage() {
        return $this->getCached('ordersCacheKey', 'getOrdersPercentage');
//        $tag = 'OrdersPercentage';
//        if ($this->cache->tags($tag)->has($this->ordersCacheKey)) {
//            return $this->cache->tags($tag)->get($this->ordersCacheKey);
//        } else {
//            $cacheContent = $this->getOrdersPercentage();
//
//            $this->cache->tags($tag)->flush();
//            $this->cache->tags($tag)->forever($this->ordersCacheKey,$cacheContent);
//            return $cacheContent;
//        }
    }

    /**
     * @return array
     */
    public function getCachedDre() {
        $tag = 'Dre';
        if ($this->cache->has($this->ordersCacheKey.$tag)) {
            return $this->cache->get($this->ordersCacheKey.$tag);
        } else {
            $cacheContent = [];
            $this->getComporPeriodos($cacheContent, Carbon::now(), Carbon::now()->subYear(1));

            $this->cache->put($this->ordersCacheKey.$tag,$cacheContent, $this->cacheMinutes);
            return $cacheContent;
        }
    }

    private function getCached($key, $call)
    {
        if ($this->cache->has($this->$key.$call)) {
            return $this->cache->get($this->$key.$call);
        } else {
            $cacheContent = $this->$call();
            $this->cache->put($this->$key.$call,$cacheContent, $this->cacheMinutes);
            return $cacheContent;
        }
    }

}