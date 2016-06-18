<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\OrderRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReportRepository;
use Carbon\Carbon;

class ReportsController extends Controller
{

    private $orderRepository;
    private $productRepository;
    private $partnerRepository;
    private $reportRepository;
    private $kmOrdersVendaEntregue = [];

    public function __construct(OrderRepository $orderRepository,
                                ReportRepository $reportRepository,
                                ProductRepository $productRepository,
                                PartnerRepository $partnerRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->partnerRepository = $partnerRepository;
        $this->productRepository = $productRepository;
        $this->reportRepository = $reportRepository;
    }

    public function estoque($host=null)
    {
        $products = $this->productRepository
            ->getProductActivatedEstoque()
            ->orderBy('nome', 'asc')
            ->get();

        $estoque = $this->orderRepository->getCachedEstoque();
        $productCost = $this->orderRepository->getCachedProductCost();

        $custoTotal=0;
        foreach ($productCost as $custo) {
            $custoTotal+=$custo;
        }

        $valorVendaTotal=0;
        foreach ($products as $product) {
            $valorVendaTotal+=isset($estoque[$product->id])?$estoque[$product->id]*$product->valorUnitVenda:0;
        }

        return view('erp.reports.estoque', compact('host'))->with([
            'products' => $products,
            'estoque' => $estoque,
            'compras' => $this->orderRepository->getCachedProductPurchase(),
            'vendas' => $this->orderRepository->getCachedProductSales(),
            'custoMedioEstoque' => $productCost,
            'custoTotal' => $custoTotal,
            'valorVendaTotal' => $valorVendaTotal,
        ]);
    }

    /**
     * @param $host
     * @return $this
     */
    public function estatOrdem($host=null){
        $arrayDaSoma = $this->orderRepository->getCachedOrdersStatistics();
        $levantamentoDeOrdens = $this->orderRepository->getCachedFinishedOrdersStatistics();

//        $this->orderRepository->getSomaMeses(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth(), $arrayDaSoma);
//        if (($quocienteOrders = count($this->orderRepository->ordersGetWiths))==0) $quocienteOrders = 1;
//        $levantamentoDeOrdens = $this->orderRepository->getLevantamentoDeOrdens();

        return view('erp.reports.estatOrdem', compact('host'))->with([
            'viewTableTipoOrdem' => view('erp.reports.partials.tableTipoOrdem')->with([
                'data' => $this->orderRepository->getCachedOrdersCount(),
                'percentage' => $this->orderRepository->getCachedOrdersPercentage(),
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

    public function dre($host=null){
        $periodos = $this->orderRepository->getCachedDre();
//        $this->orderRepository->getComporPeriodos($periodos, Carbon::now(), Carbon::now()->subYear(1));
        sort($periodos);
        return view('erp.reports.dre', compact('host','periodos'));
    }

    public function drePdf($host=null){
        $periodos = $this->orderRepository->getCachedDre();
        sort($periodos);
        $usePdf = true;
        $pdf = \App::make('dompdf.wrapper')
            ->loadView('erp.reports.dre', compact('host','periodos','usePdf'))
            ->setPaper('a2')
            ->setOrientation('landscape');
//        return $pdf->download('invoice.pdf');
        return $pdf->stream('dre.pdf');
    }

    public function diarioGeral(Order $order, $host=null){
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

    public function cardapio($host=null){
//        return view('erp.reports.clientSideCardapio',compact('host'))->with([
        return view('erp.reports.cardapio',compact('host'))->with([
            'products' => $this->orderRepository->getProductsDelivery(),
        ]);
    }

    public function cardapioPdf($host=null){
        $usePdf = true;
        $products = $this->orderRepository->getProductsDelivery();
        $pdf = \App::make('dompdf.wrapper')
            ->loadView('erp.reports.cardapio', compact('host','products','usePdf'))
            ->setPaper('a3')
            ->setOrientation('portrait');
//        return $pdf->download('invoice.pdf');
        return $pdf->stream('cardapio.pdf');
    }
}
