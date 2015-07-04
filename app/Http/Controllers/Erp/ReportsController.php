<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use Barryvdh\DomPDF\PDF;
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

        if (($quocienteOrders = count($orders))==0) $quocienteOrders = 1;
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
                    'totalOrder'=>formatPercent(count($orders)/$quocienteOrders),
                    'openedOrders'=>formatPercent(count($openedOrders)/$quocienteOrders),
                    'cancelledOrders'=>formatPercent(count($cancelledOrders)/$quocienteOrders),
                    'finishedOrders'=>formatPercent(count($finishedOrders)/$quocienteOrders),
                    'totalVenda'=>formatPercent(count($ordersVenda)/$quocienteOrders),
                    'totalCompra'=>formatPercent(count($ordersCompra)/$quocienteOrders),
                    'totalVendaEntregue'=>formatPercent(count($ordersVendaEntregue)/$quocienteOrders),
                ],
            ]),
            'viewTableValoresMensais' => view('erp.reports.partials.tableValoresMensais')->with([
                'data' => $arrayDaSoma,
            ]),
        ]);
    }

    public function dre($host, Order $order){
        $periodos = [];
        $this->comporPeriodos($periodos, $order, Carbon::now());
        sort($periodos);
        return view('erp.reports.dre', compact('host','periodos'));
    }

    public function drePdf($host, Order $order){
        $periodos = [];
        $usePdf = true;
        $this->comporPeriodos($periodos, $order, Carbon::now());
        sort($periodos);
        $pdf = \App::make('dompdf.wrapper')
            ->loadView('erp.reports.dre', compact('host','periodos','usePdf'))
            ->setPaper('a3')
            ->setOrientation('landscape');
//        return $pdf->download('invoice.pdf');
        return $pdf->stream('dre.pdf');
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
        } else return;

    }

    private function comporPeriodos(array &$periodos, Order $order, Carbon $finish_date, Carbon $start_date=null) {
        $ordersMes = $order
            ->with('type','status','payment','orderItems','orderItems.cost')
            ->whereBetween('posted_at', [$finish_date->startOfMonth()->toDateTimeString(), $finish_date->endOfMonth()->toDateTimeString()])
            ->orderBy('posted_at', 'asc' )
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
        if (count($ordersMes)>0){
            $periodos[] = [
                'title' => $finish_date->startOfMonth()->format('m/Y'),
                'ordersMes' => $this->comporDre($ordersMes),
            ];
            return $this->comporPeriodos($periodos, $order, $finish_date->subMonth());
        } else return;
    }

    private function comporDre($orders) {
        $data = [];
        $ordersFiltred = $orders->filter(function($item) {
            if ($item->type->tipo == 'ordemVenda')
                return $item;
        });
        $data['receitaBrutaDinheiro'] = 0;
        $data['receitaBrutaCartaoCredito'] = 0;
        $data['receitaBrutaCartaoDebito'] = 0;
        foreach ($ordersFiltred as $order) {
            if ($order->payment->pagamento=='vistad')
                $data['receitaBrutaDinheiro'] = $data['receitaBrutaDinheiro'] + $order->valor_total;
            if ($order->payment->pagamento=='vistacc')
                $data['receitaBrutaCartaoCredito'] = $data['receitaBrutaCartaoCredito'] + $order->valor_total;
            if ($order->payment->pagamento=='vistacd')
                $data['receitaBrutaCartaoDebito'] = $data['receitaBrutaCartaoDebito'] + $order->valor_total;
        }
        $data['receitaBruta'] = $data['receitaBrutaDinheiro'] + $data['receitaBrutaCartaoCredito'] + $data['receitaBrutaCartaoDebito'];
        $data['honorariosPaylevenCredito'] = $data['receitaBrutaCartaoCredito']*0.0339;
        $data['honorariosPaylevenDebito'] = $data['receitaBrutaCartaoDebito']*0.0269;
        $data['honorariosPayleven'] = $data['honorariosPaylevenDebito']+$data['honorariosPaylevenCredito'];
        $data['honorariosPedidosJa'] = 0;

        $ordersFiltred = $orders->filter(function($item) {
            if ($item->type->tipo == 'ordemCompra')
                return $item;
        });
        $data['custoMercadorias'] = 0;
        $data['custoLanches'] = 0;
        $data['despesasGerais'] = 0;
        $data['despesasTransporte'] = 0;
        $data['imposto'] = 0;

        foreach ($ordersFiltred as $order) {
            foreach ($order->orderItems as $item) {
                if ($item->cost->nome=='Mercadorias')
                    $data['custoMercadorias'] = $data['custoMercadorias'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Lanches')
                    $data['custoLanches'] = $data['custoLanches'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Despesas')
                    $data['despesasGerais'] = $data['despesasGerais'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Transporte')
                    $data['despesasTransporte'] = $data['despesasTransporte'] + ($item->valor_unitario*$item->quantidade);
                if ($item->cost->nome=='Impostos')
                    $data['imposto'] = $data['imposto'] + ($item->valor_unitario*$item->quantidade);

            }
        }

        $data['deducaoReceita'] = $data['honorariosPayleven']+$data['honorariosPedidosJa']+$data['imposto'];
        $data['receitaLiquida'] = $data['receitaBruta']-$data['deducaoReceita'];

        $data['custoProdutos'] = $data['custoMercadorias']+$data['custoLanches'];

        $data['margem'] = $data['receitaLiquida'] - $data['custoProdutos'];

        $data['despesas'] = $data['despesasGerais'] + $data['despesasTransporte'];

        $data['ebitda'] = $data['margem'] - $data['despesas'];

        return $data;
    }
}
