<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\OrderRequest;
use App\Models\Address;
use App\Models\CostAllocate;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;
use App\Models\SharedCurrency;
use App\Models\SharedOrderPayment;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use App\Repositories\MessagesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller {

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @var Integer
     */
    private $itemCount;

    /**
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache) {
//        $this->middleware('auth',['except'=> ['index']]);
//        $this->middleware('roles',['administrator', 'manager']);
//        $this->middleware('auth.roles',['middleware'=> 'role:editor', 'except'=> ['index']]);
//        $this->middleware('guest',['only'=> ['index','show']]);

        $this->cache = $cache;
        $this->itemCount = config('app.orderItemCountMax');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Order $order
     * @param Request $request
     * @param $host
     * @return Response
     */
	public function index(Order $order, Request $request, $host)
	{
        $params = $request->all();
        if ( !isset($params['direction']) ) $params['direction'] = false;
        if ( isset($params['sortBy']) ) $order = $order->orderBy($params['sortBy'], ($params['direction']?'asc':'desc') );
        else $order = $order->orderBy('posted_at', 'desc' )->orderBy('id', 'desc' );

        return view('erp.orders.index', compact('host'))->with([
//            'orders' => $order->all(),
            'orders' => $order->with('partner','currency','type','payment','status','address','orderItems','orderItems.product','orderItems.cost','orderItems.currency')
                ->paginate(3)->appends($params),
            'params' => ['host'=>$host]+$params,
//            'orders' => $order->cachedAll($this->cache),
//            'cache' => $this->cache,
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
	public function create($host, SharedStat $sharedStat)
	{
        for($i=0;$i<$this->itemCount;$i++){
            $itemOrders[] = new ItemOrder;
        }
//        dd(array_merge([''=>''],['1'=>'1']));
//        dd(SharedCurrency::lists('nome_universal','id')->toArray());
//        dd($sharedStat->where(['status'=>'ativado'])->first()->partners()->orderBy('nome', 'asc' )->get()->lists('nome','id')->toArray());
//        dd((($partners = $sharedStat->where(['status'=>'ativado'])->first()->partners()->orderBy('nome', 'asc' )->get())?$partners->lists('nome','id'):[]));
//        dd(array_merge([''=>''],(($partners = $sharedStat->where(['status'=>'ativado'])->first()->partners()->orderBy('nome', 'asc' )->get())?$partners->lists('nome','id'):[])));
        $partners = $sharedStat->where(['status'=>'ativado'])->first()->partners()->with('groups','status','addresses')->orderBy('nome', 'asc' );
        $products = $sharedStat->where(['status'=>'ativado'])->first()->products()->orderBy('nome', 'asc' )->get();
        return view('erp.orders.create', compact('host','products'))->with([
            'partners' => $partners->get(),
            'order' => new Order,
            'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
            'partner_list' => [''=>'']+(($partners->get())?$partners->lists('nome','id')->toArray():[]),
//            'partner_list' => [''=>''] + Partner::lists('nome','id'),
            'order_types' => SharedOrderType::lists('descricao','id')->toArray(),
            'order_payment' => SharedOrderPayment::lists('descricao','id')->toArray(),
            'status' => SharedStat::lists('descricao','id')->toArray(),
            'viewItemOrderForm' => view('erp.orders.partials.itemOrderForm')->with([
                'product_list' => [''=>''] + (($products)?$products->lists('nome','id')->toArray():[]),
//                'product_list' => [''=>''] + Product::lists('nome','id'),
                'costs' => [''=>''] + (($costs = CostAllocate::get())? $costs->lists('cost','id')->toArray():[]),
                'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
//                'itemCount' => $this->itemCount,
                'itemOrders' => $itemOrders,
            ]),
            'viewPagamentoForm' => view('erp.orders.partials.pagamentoForm'),
            'viewConsumoForm' =>  view('erp.orders.partials.consumoForm'),
            'viewTransporteForm' =>  view('erp.orders.partials.transporteForm')->with([
                'order' => new Order,
                'addresses' => [''=>''] + (($addresses = Address::get())? $addresses->lists('logradouro','id')->toArray():[]),
            ]),
            'viewAnexosForm' =>  view('erp.orders.partials.anexosForm'),
        ]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @param $host
     * @return Response
     */
	public function store(OrderRequest $request, $host)
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

//        dd($addedOrder->id);
        //Adicionando Status
        $this->syncStatus($addedOrder, $attributes['status']);

//        flash()->success(trans('delivery.flash.pedidoAdd', ['pedido' => $addedOrder->id]));
//        MessagesRepository::send(['name'=>Auth::user()->name,'email'=>Auth::user()->email]);
        flash()->overlay(trans('order.flash.orderCreated', ['ordem' => $addedOrder->id]),trans('order.flash.orderCreatedTitle'));
        return redirect(route('orders.index', $host));
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
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Order $order
     * @param SharedStat $sharedStat
     * @return Response
     */
	public function edit($host, Order $order, SharedStat $sharedStat)
	{
        $partners = $sharedStat->where(['status'=>'ativado'])->first()->partners()->with('groups','status','addresses')->orderBy('nome', 'asc' );
        $products = $sharedStat->where(['status'=>'ativado'])->first()->products()->orderBy('nome', 'asc' )->get();

        return view('erp.orders.edit', compact('host','order','products'))->with([
            'partners' => $partners->get(),
//            'order' => new Order,
            'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
            'partner_list' => [''=>''] + (($partners)?$partners->lists('nome','id')->toArray():[]),
            'order_types' => SharedOrderType::lists('descricao','id')->toArray(),
            'order_payment' => SharedOrderPayment::lists('descricao','id')->toArray(),
            'status' => SharedStat::lists('descricao','id')->toArray(),
            'viewItemOrderForm' => view('erp.orders.partials.itemOrderForm')->with([
                'product_list' => [''=>''] + (($products)?$products->lists('nome','id')->toArray():[]),
                'costs' => [''=>''] + (($costs = CostAllocate::get())? $costs->lists('cost','id')->toArray():[]),
                'currencies' => SharedCurrency::lists('nome_universal','id')->toArray(),
                'itemCount' => $this->itemCount,
                'itemOrders' => ItemOrder::where(['order_id'=>$order->id])->get(),
            ]),
            'viewPagamentoForm' => view('erp.orders.partials.pagamentoForm'),
            'viewConsumoForm' =>  view('erp.orders.partials.consumoForm'),
            'viewTransporteForm' =>  view('erp.orders.partials.transporteForm', compact('order'))->with([
//                'addresses' => [''=>''] + (($addresses = Address::get())? $addresses->lists('endereco','id')->toArray():[]),
//                'addresses' => [''=>''] + Address::lists('logradouro','id')->toArray(),
                'addresses' => [''=>''] + $order->partner()->first()->addresses->lists('logradouro','id')->toArray(),
            ]),
            'viewAnexosForm' =>  view('erp.orders.partials.anexosForm'),
        ]);

	}

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Order $order
     * @param OrderRequest $request
     * @return Response
     */
	public function update($host, Order $order, OrderRequest $request)
	{
//        dd($request);
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
                ItemOrder::find($attributes['id'.$i])->delete();
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

            ItemOrder::find($attributes['id'.$i])->update($updateItemOrder);

            $somaTotal = $somaTotal+($attributes['quantidade'.$i]*$attributes['valor_unitario'.$i]);
        }

        $order->valor_total=$somaTotal;
        $order->save();

        flash()->overlay(trans('order.flash.orderUpdated', ['ordem' => $order->id]),trans('order.flash.orderUpdatedTitle'));
        return redirect(route('orders.index', $host));
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
//        dd($order->id);
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

}
