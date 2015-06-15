<?php

namespace App\Http\Controllers\Erp;

use App\Models\Product;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{

    private $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function estoque($host, Product $product)
    {
        return view('erp.reports.estoque', compact('host'))->with([
            'products' => $product->where(['estoque'=>1])->orderBy('nome', 'asc' )->get(),
            'estoque' => $this->orderRepository->calculaEstoque(),
        ]);
    }
}
