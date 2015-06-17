<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderConfirmationsController extends Controller
{
    public function getIndex(Order $order, $host)
    {
        return view('erp.confirmations.index',compact('host'))->with([
            'orders' => $order->all()
                ->filter(function($item) {
                    if (strpos($item->status_list,'Aberto')!==false)
                        return $item;
                }),
        ]);
    }
}
