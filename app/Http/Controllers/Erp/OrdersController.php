<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Models\Address;
use App\Models\Attachment;
use App\Models\CostAllocate;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;
use App\Models\SharedCurrency;
use App\Models\SharedOrderPayment;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use App\Repositories\ImageRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
//use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller {

    /**
     * @var CacheRepository
     */
//    private $cache;

    /**
     * @var Integer
     */
    private $itemCount;
    private $attachmentCount;

    private $orderRepository;
    private $partnerRepository;
    private $productRepository;
    private $imageRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(ImageRepository $imageRepository,
                                Order $order,
                                PartnerRepository $partnerRepository,
                                ProductRepository $productRepository,
                                OrderRepository $orderRepository) {
//        $this->middleware('auth',['except'=> ['index']]);
//        $this->middleware('roles',['administrator', 'manager']);
//        $this->middleware('auth.roles',['middleware'=> 'role:editor', 'except'=> ['index']]);
//        $this->middleware('guest',['only'=> ['index','show']]);

//        $this->cache = $cache;
        $this->itemCount = config('delivery.orderItemCountMax');
        $this->attachmentCount = config('delivery.orderAttachmentCountMax');
        $this->orderRepository = $orderRepository;
        $this->partnerRepository = $partnerRepository;
        $this->productRepository = $productRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Order $order
     * @param Request $request
     * @param $host
     * @return Response
     */
	public function index(Request $request)
	{
        $params = $request->all();
        if (!isset($params['sortBy'])) $params['sortBy'] = ['posted_at','id'];
//        if (!isset($params['host'])) $params['host'] = $host;

        return view('erp.orders.index', compact('params'))->with([
            'orders' => $this->orderRepository->getOrdersSortedPaginated($params),
            'sortRoute' => 'orders.index',
            'paramsSerialized' => urlencode(serialize($params)),
        ]);
	}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $host
     * @return Response
     */
	public function getAbertas(Request $request)
	{
        $params = $request->all();
//        if (!isset($params['host'])) $params['host'] = $host;
        if (!isset($params['sortBy'])) $params['sortBy'] = ['posted_at','id'];

        return view('erp.orders.index', compact('params'))->with([
            'orders' => ($this->orderRepository->getOrdersOpenedSorted($params)),
            'sortRoute' => 'orders.abertas',
            'paramsSerialized' => urlencode(serialize($params)),
        ]);
	}

    public function getCompras(Request $request)
    {
        $params = $request->all();
//        if (!isset($params['host'])) $params['host'] = $host;
        if (!isset($params['sortBy'])) $params['sortBy'] = ['posted_at','id'];

        $orderTypeId = is_null($orderTypeId = SharedOrderType::where(['tipo'=>'ordemCompra'])->first())?null:$orderTypeId->id;

        return view('erp.orders.index', compact('params'))->with([
            'orders' => $this->orderRepository->getOrdersWhereSortedPaginated(['type_id'=>$orderTypeId], $params ),
            'sortRoute' => 'orders.compras',
            'paramsSerialized' => urlencode(serialize($params)),
        ]);

    }
    public function getVendas(Request $request)
    {
        $params = $request->all();
//        if (!isset($params['host'])) $params['host'] = $host;
        if (!isset($params['sortBy'])) $params['sortBy'] = ['posted_at','id'];

        $orderTypeId = is_null($orderTypeId = SharedOrderType::where(['tipo'=>'ordemVenda'])->first())?null:$orderTypeId->id;

        return view('erp.orders.index', compact('params'))->with([
            'orders' => $this->orderRepository->getOrdersWhereSortedPaginated(['type_id'=>$orderTypeId], $params ),
            'sortRoute' => 'orders.vendas',
            'paramsSerialized' => urlencode(serialize($params)),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $host
     * @param SharedStat $sharedStat
     * @param Order $order
     * @return Response
     */
	public function create(Order $order)
	{
        for($i=0;$i<$this->itemCount;$i++){
            $itemOrders[] = new ItemOrder;
        }

        for($i=0;$i<$this->attachmentCount;$i++){
            $attachments[] = new Attachment;
        }

//        dd($this->partnerRepository->getPartnersActivated());
//        dd($partner->partner_list->toArray());
        return view('erp.orders.create', compact('order'))->with([
            'products' => $this->productRepository->getCachedProductActivated(),
            'partners' => $this->partnerRepository->getCachedPartnersActivated(),
            'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
            'partner_list' => $this->partnerRepository->getCachedPartnersActivatedSelectList(),
            'order_types' => SharedOrderType::lists('descricao','id')->toArray(),
            'order_payment' => SharedOrderPayment::lists('descricao','id')->toArray(),
            'status' => SharedStat::lists('descricao','id')->toArray(),
            'viewItemOrderForm' => view('erp.orders.partials.itemOrderForm')->with([
                'product_list' => $this->productRepository->getCachedProductActivatedSelectList(),
                'costs' => [''=>''] + (($costs = (new CostAllocate)->orderBy('numero')->get())? $costs->lists('cost_list','id')->toArray():[]),
                'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
                'itemOrders' => $itemOrders,
            ]),
            'viewPagamentoForm' => view('erp.orders.partials.pagamentoForm'),
            'viewConsumoForm' =>  view('erp.orders.partials.consumoForm'),
            'viewTransporteForm' =>  view('erp.orders.partials.transporteForm')->with([
                'order' => new Order,
                'addresses' => [''=>''], // + (($addresses = Address::get())? $addresses->lists('logradouro','id')->toArray():[]),
            ]),
            'viewAnexosForm' =>  view('erp.orders.partials.anexosForm', compact('host'))->with([
                'attachments' => $attachments,
            ]),
        ]);
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Order $order
     * @param SharedStat $sharedStat
     * @return Response
     */
    public function edit(Order $order, Request $request)
    {
        $itemOrders = $order->orderItems;
        for($i=count($itemOrders);$i<$this->itemCount;$i++){
            $itemOrders[] = new ItemOrder;
        }

        $attachments = $order->attachments;
        for($i=count($attachments);$i<$this->attachmentCount;$i++){
            $attachments[] = new Attachment;
        }

//        dd(unserialize(urldecode($request->all()['paramsSerialized'])));
        return view('erp.orders.edit', compact('order'))->with([
            'params' => unserialize(urldecode($request->all()['paramsSerialized'])),
            'products' => $this->productRepository->getCachedProductActivated(),
            'partners' => $this->partnerRepository->getCachedPartnersActivated(),
            'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
            'partner_list' => $this->partnerRepository->getCachedPartnersActivatedSelectList(),
            'order_types' => SharedOrderType::lists('descricao','id')->toArray(),
            'order_payment' => SharedOrderPayment::lists('descricao','id')->toArray(),
            'status' => SharedStat::lists('descricao','id')->toArray(),
            'viewItemOrderForm' => view('erp.orders.partials.itemOrderForm')->with([
                'product_list' => $this->productRepository->getCachedProductActivatedSelectList(),
                'costs' => [''=>''] + (($costs = (new CostAllocate)->orderBy('numero')->get())? $costs->lists('cost_list','id')->toArray():[]),
                'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
                'itemCount' => $this->itemCount,
                'itemOrders' => $itemOrders,
            ]),
            'viewPagamentoForm' => view('erp.orders.partials.pagamentoForm'),
            'viewConsumoForm' =>  view('erp.orders.partials.consumoForm'),
            'viewTransporteForm' =>  view('erp.orders.partials.transporteForm', compact('order'))->with([
//                'addresses' => [''=>''] + (($addresses = Address::get())? $addresses->lists('endereco','id')->toArray():[]),
//                'addresses' => [''=>''] + Address::lists('logradouro','id')->toArray(),
                'addresses' => [''=>''] + $order->partner()->first()->addresses->lists('logradouro','id')->toArray(),
            ]),
            'viewAnexosForm' =>  view('erp.orders.partials.anexosForm', compact('host'))->with([
                'attachments' => $attachments,
            ]),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @param $host
     * @return Response
     */
	public function store(OrderRequest $request)
	{
//		dd($request->all());
        $attributes = $request->all();

        //Adicionando a Ordem
        $addedOrder = $this->getAddedOrder($attributes);

        //Adicionando os itens do pedido
        $somaTotal = 0;
        for($i=0;$i<$this->itemCount;$i++){
            if (! $attributes['quantidade'.$i]>0) continue;
            $somaTotal = $somaTotal+($attributes['quantidade'.$i]*$attributes['valor_unitario'.$i]);
            $addedItemOrder = $this->getAddedItemOrder($attributes,$i);
            $addedOrder->orderItems()->save($addedItemOrder);
        }
        $addedOrder->valor_total=$somaTotal;
        $addedOrder->save();

        //Adicionando os anexos
        for($i=0;$i<$this->attachmentCount;$i++){
            if (isset($attributes['file'.($i+1)]) && !is_null($newFile = $this->imageRepository->saveAttachment($request, 'file'.($i+1), $addedOrder->id.'-'.Carbon::now()->timestamp) ) ) {
                if (!empty($attributes['file'.($i+1)])){
                    $attributes['file'.($i+1)] = $newFile;
                    $addedAttachment = $this->getAddedAttachment($attributes,($i+1));
                    $addedOrder->attachments()->save($addedAttachment);
                }
            }
        }

        //Adicionando Status
        $this->syncStatus($addedOrder, $attributes['status']);

//        flash()->success(trans('delivery.flash.pedidoAdd', ['pedido' => $addedOrder->id]));
//        MessagesRepository::send(['name'=>Auth::user()->name,'email'=>Auth::user()->email]);
        flash()->overlay(trans('order.flash.orderCreated', ['ordem' => $addedOrder->id]),trans('order.flash.orderCreatedTitle'));
        return redirect(route('orders.index'));
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}



    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Order $order
     * @param OrderRequest $request
     * @return Response
     */
	public function update(Order $order, OrderRequest $request)
	{
//        dd(route('orders.index', [$host]+$request->only('direction','sortBy','page')));
        $attributes = $request->all();
        $updateOrder = [
            '' => $attributes,
            'partner_id' => $attributes['partner_id'],
            'address_id' => $attributes['address_id'],
            'currency_id' => $attributes['currency_id'],
            'type_id' => $attributes['type_id'],
            'payment_id' => $attributes['payment_id'],
            'posted_at' => $attributes['posted_at'],
        ];
        if (isset($attributes['valor_total'])) $updateOrder['valor_total'] = $attributes['valor_total'];
        if (isset($attributes['desconto_total'])) $updateOrder['desconto_total'] = $attributes['desconto_total'];
        if (isset($attributes['troco'])) $updateOrder['troco'] = $attributes['troco'];
        if (isset($attributes['descricao'])) $updateOrder['descricao'] = $attributes['descricao'];
        if (isset($attributes['referencia'])) $updateOrder['referencia'] = $attributes['referencia'];
        if (isset($attributes['obsevacao'])) $updateOrder['obsevacao'] = $attributes['obsevacao'];
        $order->update($updateOrder);

        //Atualizando Status
//        dd($attributes['status']);
        $this->syncStatus($order, $attributes['status']);

        //Atualizando os itens do pedido
        $somaTotal = 0;
//        for($i=0;$i<$this->itemCount;$i++){
        for($i=0;isset($attributes['id'.$i]);$i++) {
            if (! $attributes['quantidade'.$i]>0) {
                if (!is_null($item = ItemOrder::find($attributes['id'.$i]) )){
                    $item->delete();
                }
                continue;
            }
//            dd($attributes['id'.$i]);
//            dd(ItemOrder::find($attributes['id'.$i])->toArray());
            $updateItemOrder = [
                'cost_id' => $attributes['cost_id'.$i],
                'product_id' => $attributes['product_id'.$i],
                'currency_id' => $attributes['item_currency_id'.$i],
                'quantidade' => $attributes['quantidade'.$i],
                'valor_unitario' => $attributes['valor_unitario'.$i],
            ];
            if (isset($attributes['desconto_unitario'.$i])) $updateItemOrder['desconto_unitario'] = $attributes['desconto_unitario'.$i];
            if (isset($attributes['descricao'.$i])) $updateItemOrder['descricao'] = $attributes['descricao'.$i];

            if (!is_null($item = ItemOrder::find($attributes['id'.$i]))){
                $item->update($updateItemOrder);
            }else{
                $addedItemOrder = $this->getAddedItemOrder($attributes,$i);
                $order->orderItems()->save($addedItemOrder);
            }

            $somaTotal = $somaTotal+($attributes['quantidade'.$i]*$attributes['valor_unitario'.$i]);
        }

        //Adicionando os anexos
        for($i=0;$i<$this->attachmentCount;$i++){
            if (isset($attributes['file'.($i+1)]) && !is_null($newFile = $this->imageRepository->saveAttachment($request, 'file'.($i+1), $order->id.'-'.Carbon::now()->timestamp) ) ) {
                if (!empty($attributes['file'.($i+1)])){
                    $attributes['file'.($i+1)] = $newFile;
                    $addedAttachment = $this->getAddedAttachment($attributes,($i+1));
                    $order->attachments()->save($addedAttachment);
                }
            }
        }

        $order->valor_total=$somaTotal;
        $order->save();

        flash()->overlay(trans('order.flash.orderUpdated', ['ordem' => $order->id]),trans('order.flash.orderUpdatedTitle'));
        return redirect(route('orders.index', $request->only('direction','sortBy','page')));
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param $host
     * @param Order $order
     * @param OrderRequest $request
     * @return Response
     */
	public function destroy($host, Order $order)
	{
		$order->delete();
        flash()->overlay(trans('order.flash.orderDeleted', ['ordem' => $order->id]),trans('order.flash.orderDeletedTitle'));
        return redirect(route('orders.index', $host));
	}

    /**
     * Add Order
     *
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getAddedOrder($attributes)
    {
        $orderAttribute = [
            'mandante' => Auth::user()->mandante,
            'partner_id' => $attributes['partner_id'],
            'address_id' => $attributes['address_id'],
            'posted_at' => $attributes['posted_at'],
            'currency_id' => $attributes['currency_id'],
            'type_id' => $attributes['type_id'],
            'payment_id' => $attributes['payment_id'],
        ];
        if (!empty($attributes['valor_total'])) $orderAttribute['valor_total'] = $attributes['valor_total'];
        if (!empty($attributes['desconto_total'])) $orderAttribute['desconto_total'] = $attributes['desconto_total'];
        if (!empty($attributes['troco'])) $orderAttribute['troco'] = $attributes['troco'];
        if (!empty($attributes['descricao'])) $orderAttribute['descricao'] = $attributes['descricao'];
        if (!empty($attributes['referencia'])) $orderAttribute['referencia'] = $attributes['referencia'];
        if (!empty($attributes['observacao'])) $orderAttribute['observacao'] = $attributes['observacao'];

//        dd($orderAttribute);
        return Order::create($orderAttribute);
    }

    /**
     * Sync up a list of status in the database.
     *
     * @param Order $order
     * @param array $status
     */
    private function syncStatus(Order $order, $status)
    {
//        dd($status);
        $order->status()->sync(is_null($status)?[]:$status);
    }

    private function getAddedItemOrder($attributes, $key)
    {
        $itemOrderAttribute = [
            'mandante' => Auth::user()->mandante,
            'product_id' => $attributes['product_id'.$key],
            'cost_id' => $attributes['cost_id'.$key],
            'currency_id' => $attributes['item_currency_id'.$key],
            'quantidade' => $attributes['quantidade'.$key],
            'valor_unitario' => $attributes['valor_unitario'.$key],
        ];
        if (!empty($attributes['desconto_unitario'.$key])) $orderAttribute['desconto_unitario'] = $attributes['desconto_unitario'.$key];
        if (!empty($attributes['currency_id'.$key])) $orderAttribute['currency_id'] = $attributes['currency_id'.$key];
//        dd($itemOrderAttribute);
        return new ItemOrder($itemOrderAttribute);
    }

    private function getAddedAttachment($attributes, $key)
    {
        $itemAttribute = [
            'mandante' => Auth::user()->mandante,
            'file' => $attributes['file'.$key],
        ];

        return new Attachment($itemAttribute);
    }

}
