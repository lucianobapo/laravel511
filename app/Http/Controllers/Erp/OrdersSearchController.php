<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use App\Models\SharedOrderType;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrdersSearchController extends Controller
{
    private $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function getCompras(Order $order, Request $request, $host)
    {
        $params = $request->all();

        $orderTypeId = is_null($orderTypeId = SharedOrderType::where(['tipo'=>'ordemCompra'])->first())?null:$orderTypeId->id;

        $params['sortBy'] = ['posted_at','id'];
        return view('erp.orders.index', compact('host'))->with([
            'orders' => $this->orderRepository->getOrdersWhereSortedPaginated(['type_id'=>$orderTypeId], [
                'partner',
                'currency',
                'type',
                'payment',
                'status',
                'address',
                'orderItems',
                'orderItems.product',
                'orderItems.cost',
                'orderItems.currency',
                'attachments',
            ], $params ),
            'params' => ['host'=>$host]+$params,
        ]);

    }
    public function getVendas(Order $order, Request $request, $host)
    {
        $params = $request->all();
        $orderTypeId = is_null($orderTypeId = SharedOrderType::where(['tipo'=>'ordemVenda'])->first())?null:$orderTypeId->id;

        $params['sortBy'] = ['posted_at','id'];
        return view('erp.orders.index', compact('host'))->with([
            'orders' => $this->orderRepository->getOrdersWhereSortedPaginated(['type_id'=>$orderTypeId], [
                'partner',
                'currency',
                'type',
                'payment',
                'status',
                'address',
                'orderItems',
                'orderItems.product',
                'orderItems.cost',
                'orderItems.currency',
                'attachments',
            ], $params ),
            'params' => ['host'=>$host]+$params,
        ]);

    }
}
