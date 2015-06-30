<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

    private $orderRepository;
    private $kmOrdersVendaEntregue = [];

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function estoque($host, Product $product)
    {
        $saldos = $this->orderRepository->calculaEstoque();

        return view('erp.reports.estoque', compact('host'))->with([
            'products' => $product->where(['estoque'=>1])->orderBy('nome', 'asc' )->get(),
            'estoque' => $saldos['estoque'],
            'custoMedioEstoque' => $saldos['custoMedio'],
            'custoSubTotal' => $saldos['custoMedioSubTotal'],
            'custoTotal' => $saldos['custoTotal'],
            'valorVenda' => $saldos['valorVenda'],
            'valorVendaTotal' => $saldos['valorVendaTotal'],
        ]);
    }

    public function estatOrdem($host, Order $order){
        $arrayDaSoma = [];
        $this->somaMeses($order, Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), $arrayDaSoma);

        $orders = $order->with('type','status','confirmations')->get();

        $finishedOrders = $orders
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
        $openedOrders = $orders
            ->filter(function($item) {
                if (strpos($item->status_list,'Aberto')!==false)
                    return $item;
            });
        $cancelledOrders = $orders
            ->filter(function($item) {
                if (strpos($item->status_list,'Cancelado')!==false)
                    return $item;
            });

        $ordersVenda = $finishedOrders
            ->filter(function($item) {
                if (!!array_search('ordemVenda',$item->type->toArray()))
                    return $item;
            });
        $ordersCompra = $finishedOrders
            ->filter(function($item) {
                if (!!array_search('ordemCompra',$item->type->toArray()))
                    return $item;
            });

        $ordersVendaEntregue = $ordersVenda
            ->filter(function($item) {
                if ($item->hasConfirmation('entregando')
                    && $item->hasConfirmation('entregue')
                    && ($item->kmFinal>$item->kmInicial)
                ){
                    $this->kmOrdersVendaEntregue[$item->id] = $item->kmFinal-$item->kmInicial;
                    return $item;
                }
            });

//        dd($this->kmOrdersVendaEntregue);

        return view('erp.reports.estatOrdem', compact('host'))->with([
            'viewTableTipoOrdem' => view('erp.reports.partials.tableTipoOrdem')->with([
                'data' => [
                    'totalOrder'=>count($orders),
                    'openedOrders'=>count($openedOrders),
                    'cancelledOrders'=>count($cancelledOrders),
                    'finishedOrders'=>count($finishedOrders),
                    'totalVenda'=>count($ordersVenda),
                    'totalCompra'=>count($ordersCompra),
                    'totalVendaEntregue'=>count($ordersVendaEntregue),
                ],
                'percentage' => [
                    'totalOrder'=>formatPercent(count($orders)/count($orders)),
                    'openedOrders'=>formatPercent(count($openedOrders)/count($orders)),
                    'cancelledOrders'=>formatPercent(count($cancelledOrders)/count($orders)),
                    'finishedOrders'=>formatPercent(count($finishedOrders)/count($orders)),
                    'totalVenda'=>formatPercent(count($ordersVenda)/count($orders)),
                    'totalCompra'=>formatPercent(count($ordersCompra)/count($orders)),
                    'totalVendaEntregue'=>formatPercent(count($ordersVendaEntregue)/count($orders)),
                ],
            ]),
            'viewTableValoresMensais' => view('erp.reports.partials.tableValoresMensais')->with([
                'data' => $arrayDaSoma,
            ]),
        ]);
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

    public function somaMeses(Order $order, Carbon $from, Carbon $to, array &$arrayDaSoma){
        $ordersMes = $order
            ->with('type','status')
            ->whereBetween('posted_at', [$from->toDateTimeString(), $to->toDateTimeString()])
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
        if (count($ordersMes)>0){
            $arrayDaSoma[$from->format('m/Y')] = $this->somaValorOrdensMes($ordersMes);
            return $this->somaMeses($order, $from->subMonth(), $to->subMonth(),$arrayDaSoma);
        }

    }
}
