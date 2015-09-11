<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ReportRepository;
use Carbon\Carbon;

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

    /**
     * @param $host
     * @return $this
     */
    public function estatOrdem($host){
        $arrayDaSoma = [];
        $this->orderRepository->getSomaMeses(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), $arrayDaSoma);

        if (($quocienteOrders = count($this->orderRepository->ordersGetWithTypeStatusConfirmations))==0) $quocienteOrders = 1;

        $levantamentoDeOrdens = $this->orderRepository->getLevantamentoDeOrdens();
        return view('erp.reports.estatOrdem', compact('host'))->with([
            'viewTableTipoOrdem' => view('erp.reports.partials.tableTipoOrdem')->with([
                'data' => [
                    'totalOrder'=>count($this->orderRepository->ordersGetWithTypeStatusConfirmations),
                    'openedOrders'=>count($this->orderRepository->getOrdersOpened()),
                    'cancelledOrders'=>count($this->orderRepository->getOrdersCanceled()),
                    'finishedOrders'=>count($this->orderRepository->getOrdersFinished()),
                    'totalVenda'=>count($this->orderRepository->getSalesOrdersFinished()),
                    'totalCompra'=>count($this->orderRepository->getPurchaseOrdersFinished()),
                    'totalVendaEntregue'=>count($this->orderRepository->getSalesOrdersFinishedDelivered()),
                ],
                'percentage' => [
                    'totalOrder'=>formatPercent(count($this->orderRepository->ordersGetWithTypeStatusConfirmations)/$quocienteOrders),
                    'openedOrders'=>formatPercent(count($this->orderRepository->getOrdersOpened())/$quocienteOrders),
                    'cancelledOrders'=>formatPercent(count($this->orderRepository->getOrdersCanceled())/$quocienteOrders),
                    'finishedOrders'=>formatPercent(count($this->orderRepository->getOrdersFinished())/$quocienteOrders),
                    'totalVenda'=>formatPercent(count($this->orderRepository->getSalesOrdersFinished())/$quocienteOrders),
                    'totalCompra'=>formatPercent(count($this->orderRepository->getPurchaseOrdersFinished())/$quocienteOrders),
                    'totalVendaEntregue'=>formatPercent(count($this->orderRepository->getSalesOrdersFinishedDelivered())/$quocienteOrders),
                ],
            ]),
            'viewTableValoresMensais' => view('erp.reports.partials.tableValoresMensais')->with([
                'data' => $arrayDaSoma,
            ]),
            'viewTableOrdensPorMes' => view('erp.reports.partials.tableOrdensPorMes')->with([
                'data' => $levantamentoDeOrdens['ordensMes'],
                'dataValor' => $levantamentoDeOrdens['ordensMesValor'],
                'soma' => $levantamentoDeOrdens['somaOrdensMes'],
                'somaValor' => $levantamentoDeOrdens['somaOrdensMesValor'],
            ]),
            'viewTableOrdensPorDia' => view('erp.reports.partials.tableOrdensPorDia')->with([
                'data' => $levantamentoDeOrdens['ordensDiaDoMes'],
                'dataPosicao' => $levantamentoDeOrdens['ordensDiaDoMesPosicao'],
                'dataValor' => $levantamentoDeOrdens['ordensDiaDoMesValor'],
                'dataValorPosicao' => $levantamentoDeOrdens['ordensDiaDoMesValorPosicao'],
                'soma' => $levantamentoDeOrdens['somaOrdensDiaDoMes'],
                'somaValor' => $levantamentoDeOrdens['somaOrdensDiaDoMesValor'],
            ]),
            'viewTableOrdensPorSemana' => view('erp.reports.partials.tableOrdensPorSemana')->with([
                'data' => $levantamentoDeOrdens['ordensSemana'],
                'dataPosicao' => $levantamentoDeOrdens['ordensSemanaPosicao'],
                'dataValor' => $levantamentoDeOrdens['ordensSemanaValor'],
                'dataValorPosicao' => $levantamentoDeOrdens['ordensSemanaValorPosicao'],
                'soma' => $levantamentoDeOrdens['somaOrdensSemana'],
                'somaValor' => $levantamentoDeOrdens['somaOrdensSemanaValor'],
            ]),
            'viewTableOrdensPorHora' => view('erp.reports.partials.tableOrdensPorHora')->with([
                'data' => $levantamentoDeOrdens['ordensHora'],
                'dataPosicao' => $levantamentoDeOrdens['ordensHoraPosicao'],
                'dataValor' => $levantamentoDeOrdens['ordensHoraValor'],
                'dataValorPosicao' => $levantamentoDeOrdens['ordensHoraValorPosicao'],
                'soma' => $levantamentoDeOrdens['somaOrdensHora'],
                'somaValor' => $levantamentoDeOrdens['somaOrdensHoraValor'],
            ]),
        ]);
    }

    public function dre($host){
        $periodos = [];
        $this->orderRepository->getComporPeriodos($periodos, Carbon::now(), Carbon::now()->subYear(1));
        sort($periodos);
        return view('erp.reports.dre', compact('host','periodos'));
    }

    public function drePdf($host){
        $periodos = [];
        $usePdf = true;
        $this->orderRepository->getComporPeriodos($periodos, Carbon::now());
        sort($periodos);
        $pdf = \App::make('dompdf.wrapper')
            ->loadView('erp.reports.dre', compact('host','periodos','usePdf'))
            ->setPaper('a2')
            ->setOrientation('landscape');
//        return $pdf->download('invoice.pdf');
        return $pdf->stream('dre.pdf');
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
//        return view('erp.reports.clientSideCardapio',compact('host'))->with([
        return view('erp.reports.cardapio',compact('host'))->with([
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
        $ord = $this->orderRepository->getLevantamentoDeOrdens();

        $usr = $this->partnerRepository->getLevantamentoDeParceiros();

        dd($usr+$ord);
    }

}
