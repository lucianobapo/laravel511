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
//    private $orderRepository;
    public $estoque;

    /**
     * @param Order $order
     */
    public function __construct(Order $order) {
        $this->order = $order;
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
    public function getOrdersBase($withs=[]) {
        return $this->order
            ->with($withs);
    }

    /**
     * @return Order
     */
    public function getOrdersBaseWhereSorted($wheres=[], $withs=[], &$params=[], $defaultColumn='id', $defaultDirection=false) {

        if (!isset($params['direction'])) $params['direction'] = $defaultDirection;
        if (!isset($params['sortBy'])) $params['sortBy'] = $defaultColumn;

        $result = $this->getOrdersBase($withs)
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
    public function getOrdersFinished() {
        $params['sortBy'] = ['posted_at','id'];
        return $this->getOrdersBaseWhereSorted([], ['status','type'], $params)
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
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
        $ordensFiltradas = $this->getSalesOrdersFinished();
        foreach($ordensFiltradas as $order){
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