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

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function estoque($host, Product $product)
    {
        $saldos = $this->orderRepository->calculaEstoque();
//        $custoMedioEstoque = [];
//        $custoSubTotal = [];
//        $custoTotal = 0;
//        foreach($saldos['estoque'] as $id=>$productEstoque){
//            if ($productEstoque>0) {
//                $custoMedioEstoque[$id] = $this->orderRepository->calculaCustoMedioEstoque($id);
//                $custoSubTotal[$id] = $custoMedioEstoque[$id]*$productEstoque;
//                $custoTotal = $custoTotal + $custoSubTotal[$id];
//            }
//        }

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
//        $query = DB::table('orders')
//            ->join('shared_order_types as s', 'orders.type_id', '=', 's.id')
//            ->where('s.tipo', '=', 'ordemVenda')
//            ->get();

        $arrayDaSoma = [];
        $from = Carbon::now()->startOfMonth();
        $to = Carbon::now()->endOfMonth();
        $this->somaMeses($order, $from, $to, $arrayDaSoma);
//        dd($arrayDaSoma);

        $orders = $order->with('type','status')
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });

        $ordersVenda = $orders
            ->filter(function($item) {
                if (!!array_search('ordemVenda',$item->type->toArray()))
                    return $item;
            });
        $ordersCompra = $orders
            ->filter(function($item) {
                if (!!array_search('ordemCompra',$item->type->toArray()))
                    return $item;
            });

        return view('erp.reports.estatOrdem', compact('host'))->with([
            'viewTableTipoOrdem' => view('erp.reports.partials.tableTipoOrdem')->with([
                'data' => [
                    'totalOrder'=>count($orders),
                    'totalVenda'=>count($ordersVenda),
                    'totalCompra'=>count($ordersCompra),
                ],
                'percentage' => [
                    'totalOrder'=>(count($orders)*100)/count($orders),
                    'totalVenda'=>(count($ordersVenda)*100)/count($orders),
                    'totalCompra'=>(count($ordersCompra)*100)/count($orders),
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
//        $ordersMes = $order->whereBetween('posted_at', [$from, $to])->with('type')->get();
        $data['vendas'] = 0;
        $data['compras'] = 0;
        foreach ($ordersMes as $orderValue) {
            if ($orderValue->type->tipo == 'ordemVenda') {
                $data['vendas'] = $data['vendas'] + $orderValue->valor_total;
            }
            if ($orderValue->type->tipo == 'ordemCompra') {
                $data['compras'] = $data['compras'] + $orderValue->valor_total;
            }
        }
        return $data;
    }

    public function somaMeses(Order $order, Carbon $from, $to, &$arrayDaSoma){
        $ordersMes = $order->whereBetween('posted_at', [$from->toDateTimeString(), $to->toDateTimeString()])
            ->with('type','status')
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
