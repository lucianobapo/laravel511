<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

    private $orderRepository;
    private $partnerRepository;
    private $reportRepository;
    private $estoque;
    private $kmOrdersVendaEntregue = [];

    public function __construct(OrderRepository $orderRepository, ReportRepository $reportRepository, PartnerRepository $partnerRepository) {
        $this->orderRepository = $orderRepository;
        $this->partnerRepository = $partnerRepository;
        $this->reportRepository = $reportRepository;
        $this->estoque = $this->orderRepository->calculaEstoque();
    }

    public function estoque($host, Product $product)
    {
        $saldos = $this->estoque;

        return view('erp.reports.estoque', compact('host'))->with([
            'products' => $product->where(['estoque'=>1])
                ->orderBy('nome', 'asc' )
                ->get()
                ->filter(function($item) {
                    if (strpos($item->status_list,'Desativado')===false)
                        return $item;
                }),
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
        $this->comporPeriodos($periodos, $order, Carbon::now(), Carbon::now()->subYear(1));
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
            ->setPaper('a2')
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
        if ( (count($ordersMes)>0) && ($finish_date>$start_date) ){
            $periodos[] = [
                'title' => $finish_date->startOfMonth()->format('m/Y'),
                'ordersMes' => $this->comporDre($ordersMes, $this->estoque),
            ];
            return $this->comporPeriodos($periodos, $order, $finish_date->subMonth(), $start_date);
        } else return;
    }

    private function comporDre($orders, array $estoque=[]) {
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

    public function diarioGeral($host, Order $order){
        $orders = $order
            ->with(['type','status','orderItems','orderItems.cost','orderItems.product'])
            ->get()
            ->filter(function($item) {
                if (strpos($item->status_list,'Finalizado')!==false)
                    return $item;
            });
//        dd($this->reportRepository->preparaReceitaVendas($orders));
        return view('erp.reports.diarioGeral',compact('host'))->with([
            'viewTableOrdens' => view('erp.reports.partials.diarioGeralTableOrdens')->with([
//                'orders' => $orders,
                'diario' => $this->reportRepository->preparaReceitaVendas($orders),
            ]),
        ]);
    }

    public function cardapio($host){
        return view('erp.reports.clientSideCardapio',compact('host'))->with([
            'products' => $this->orderRepository->getProductsDelivery(),
        ]);
    }

    public function cardapioPdf($host){
        $usePdf = true;
        $products = $this->orderRepository->getProductsDelivery();
        $pdf = \App::make('dompdf.wrapper')
            ->loadView('erp.reports.cardapio', compact('host','products','usePdf'))
            ->setPaper('a3')
            ->setOrientation('portrait');
//        return $pdf->download('invoice.pdf');
        return $pdf->stream('cardapio.pdf');
    }

    public function estatOrdemFinalizadas() {

        $ordensFiltradas = $this->orderRepository->getSalesOrdersFinished();
//        $ordensFiltradas = $this->orderRepository->getSalesOrdersFinished()
//            ->filter(function($item) {
//                if ($item->posted_at_carbon->format('H:i')!='01:00')
//                    return $item;
//            });



        foreach($ordensFiltradas as $order){
            $indexMes = $order->posted_at_carbon->format('m');
            $mes[$indexMes] = isset($mes[$indexMes])?$mes[$indexMes]+1:1;

            $indexDiaMes = $order->posted_at_carbon->format('d');
            $diaMes[$indexDiaMes] = isset($diaMes[$indexDiaMes])?$diaMes[$indexDiaMes]+1:1;

            $indexSemana = $order->posted_at_carbon->format('w-l');
            $semana[$indexSemana] = isset($semana[$indexSemana])?$semana[$indexSemana]+1:1;

            if (($order->posted_at_carbon->format('H:i')!='01:00')&&($order->posted_at_carbon->format('H:i')!='00:00')){
                $indexHora = $order->posted_at_carbon->format('H');
                $hora[$indexHora] = isset($hora[$indexHora])?$hora[$indexHora]+1:1;
            }
//            $indexUsuario = $order->partner->nome;
//            $usuario[$indexUsuario] = isset($usuario[$indexUsuario])?$usuario[$indexUsuario]+1:1;
//            if ($order->posted_at_carbon<=Carbon::now()->subMonth()){
//                $indexUsuarioDesativado = $order->partner->nome;
//                $usuarioDesativado[$indexUsuarioDesativado] = isset($usuarioDesativado[$indexUsuarioDesativado])?$usuarioDesativado[$indexUsuarioDesativado]+1:1;
//            }
        }

        $usuariosFiltrados = $this->partnerRepository->getPartnersActivatedWithOrder();

        foreach ($usuariosFiltrados as $partner){
            if (count($partner->orders)>0) {
                $usuarioNovo = false;
                foreach ($partner->orders as $order) {
                    if ($order->type->tipo=='ordemVenda') {
                        $usuarios[$partner->nome] = isset($usuarios[$partner->nome])?$usuarios[$partner->nome]+1:1;
                        $usuariosValor[$partner->nome] = isset($usuariosValor[$partner->nome])?$usuariosValor[$partner->nome]+$order->valor_total:$order->valor_total;

                        $usuariosAntigos[$partner->nome] = isset($usuariosAntigos[$partner->nome])?$usuariosAntigos[$partner->nome]+1:1;
                        $usuariosAntigosValor[$partner->nome] = isset($usuariosAntigosValor[$partner->nome])?$usuariosAntigosValor[$partner->nome]+$order->valor_total:$order->valor_total;

                        if ($order->posted_at_carbon>Carbon::now()->subMonth()){
                            $usuarioNovo = true;
                        }
                    }
                }
                if ($usuarioNovo){
                    unset($usuariosAntigos[$partner->nome]);
                    unset($usuariosAntigosValor[$partner->nome]);
                }
            }//dd($partner->orders);

        }

        arsort($usuarios);
        arsort($usuariosValor);

        arsort($usuariosAntigos);
        arsort($usuariosAntigosValor);

        $usr = [
            '$usuarios'=>$usuarios,
            'soma$usuarios'=>array_sum($usuarios),

            '$usuariosValor'=>$usuariosValor,
            'soma$usuariosValor'=>array_sum($usuariosValor),

            '$usuariosAntigos'=>$usuariosAntigos,
            'soma$usuariosAntigos'=>array_sum($usuariosAntigos),

            '$usuariosAntigosValor'=>$usuariosAntigosValor,
            'soma$usuariosAntigosValor'=>array_sum($usuariosAntigosValor),
        ];

        ksort($diaMes);
        ksort($semana);
        ksort($hora);

        $ord = [
            '$mes'=>$mes,
            'soma$mes'=>array_sum($mes),
            '$diaMes'=>$diaMes,
            'soma$diaMes'=>array_sum($diaMes),
            '$semana'=>$semana,
            'soma$semana'=>array_sum($semana),
            '$hora'=>$hora,
            'soma$hora'=>array_sum($hora),
//            '$usuario'=>$usuario,
//            'somac'=>array_sum($usuario),
//            '$usuarioDesativado'=>$usuarioDesativado,
//            'somad'=>array_sum($usuarioDesativado),
        ];

        return $usr+$ord;
    }

    public function estatUsuarios() {
        $ordensFiltradas = $this->orderRepository->getSalesOrdersFinished();
    }
}
