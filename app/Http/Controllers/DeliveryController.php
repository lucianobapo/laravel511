<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRequest;
use App\Models\Address;
use App\Models\Contact;
use App\Models\CostAllocate;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\Product;

use App\Models\ProductGroup;
use App\Models\SharedCurrency;
use App\Models\SharedOrderPayment;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use App\Repositories\MessagesRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Cache\Repository as CacheRepository;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class DeliveryController extends Controller {

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * TotalCart
     * @var integer
     */
    private $totalCart = 0;

    /**
     * CartView
     * @var \Illuminate\View\View
     */
    private $cartView;

    private $orderRepository;
    private $productRepository;

    /**
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache, OrderRepository $orderRepository, ProductRepository $productRepository) {
//        $this->middleware('auth',['except'=> ['index','show']]);
//        $this->middleware('guest',['only'=> ['index','show']]);
//        $this->middleware('after');

        $this->cache = $cache;
        $this->cartView = view('delivery.partials.cartVazio');
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function index(Product $product){
        if (Session::has('cart')){
            $totalCart = formatBRL(Cart::subtotal());
            $cartView = view('delivery.partials.cart', compact('host'))->with([
                'cart' => Cart::content()->toArray(),
            ]);
        } else {
            $totalCart = 0;
            $cartView = view('delivery.partials.cartVazio');
        }

        $estoque = $this->orderRepository->getCachedEstoque();

        if(count($products = $this->productRepository->getProductsDelivery($estoque) ) ) {
            $panelBody = view('delivery.partials.productList', compact('host'))->with([
                'products' => $product,
                'porcoes' => $this->productRepository->getProductsCategoria($estoque, 'Porções'),
                'lanches' => $this->productRepository->getProductsCategoria($estoque, 'Lanches'),
                'cervejas' => $this->productRepository->getProductsCategoria($estoque, 'Cervejas'),
                'vinhos' => $this->productRepository->getProductsCategoria($estoque, 'Vinhos'),
                'destilados' => $this->productRepository->getProductsCategoria($estoque, 'Destilados'),
                'refrigerantes' => $this->productRepository->getProductsCategoria($estoque, 'Refrigerantes'),
                'energeticos' => $this->productRepository->getProductsCategoria($estoque, 'Energéticos'),
                'tabacaria' => $this->productRepository->getProductsCategoria($estoque, 'Tabacaria'),
                'sucos' => $this->productRepository->getProductsCategoria($estoque, 'Sucos'),
                'outros' => $this->productRepository->getProductsCategoria($estoque, 'Outros'),
//                'estoque' => $this->orderRepository->calculaEstoque()['estoque'],
                'estoque' => $estoque,
            ]);
        } else {
            $panelBody = trans('delivery.index.semProdutos');
        }

        return view('delivery.index', compact(
            'host',
            'cartView',
            'totalCart',
            'panelBody'
        ))->with([
//            'panelTitle' => trans('delivery.index.panelTitle'),
            'brand'=>'',
        ]);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addCart(Request $request){
        $data = $request->all();
        foreach($data['quantidade'] as $key => $value){
            if (!$value>0) continue;
            Cart::add($key, $data['nome'][$key], $value, $data['valor'][$key]);
            if(!$request->ajax()) flash()->success(trans('delivery.flash.itemAdd'));
        }
        if($request->ajax()) {
            return Response::json( [
                'view' => view('delivery.partials.cart')->with([
                    'cart' => Cart::content()->toArray(),
                ])->render(),
                'total' => formatBRL(Cart::subtotal()),
                'btnPedido' => link_to_route('delivery.pedido', trans('delivery.nav.cartBtn'), null, ['class'=>'btn btn-success']),
            ]);
        }
        else return redirect(route('delivery.index'));
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function pedido(Product $product, Request $request, $host='delivery'){

        if (Auth::guest()) {
            $panelListaEnderecos = '';
            $panelGuest = view('delivery.partials.panelGuestLogin', compact('host'));
        } else {
            $panelListaEnderecos = view('delivery.partials.pedidoListEnderecos')->with([
                'enderecos' => Address::where([
                    'partner_id' => Partner::firstByAttributes([
                        'user_id' => Auth::user()->id,
                    ])->id,
                ])->get(),
            ]);
            $panelGuest = '';
        }

        if (Session::has('cart')){
            $totalCart = formatBRL(Cart::subtotal());
            $cartView = view('delivery.partials.cart', compact('host'))->with([
                'cart' => Cart::content()->toArray(),
            ]);
            $panelBody = view('delivery.partials.pedidoList', compact(
                'product', 'host', 'totalCart'
            ))->with([
                'cart' => Cart::content()->toArray(),

            ]);
            $panelFormBody = view('delivery.partials.pedidoForm', compact('product', 'host'))->with([
                'cart' => Cart::content()->toArray(),
                'totalCartUnformatted' => Cart::subtotal(),
                'panelListaEnderecos' => $panelListaEnderecos,
            ]);
        } else {
            $totalCart = 0;
            $cartView = view('delivery.partials.cartVazio');
            $panelBody = view('delivery.partials.pedidoVazio', compact('host'));
            $panelFormBody = '';
        }
        return view('delivery.pedido', compact(
            'host',
            'cartView',
            'totalCart',
            'panelBody',
            'panelFormBody',
            'panelGuest'
        ))->with([
            'panelTitle' => trans('delivery.pedidos.panelTitle'),
            'brand'=>app('html')->image('/img/logo.png', trans('delivery.nav.logoAlt'), [
                'title'=>trans('delivery.nav.logoTitle'),
                'style'=>'max-height: 100%;']),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function emptyCart($host='delivery'){
        if (Session::has('cart')) Session::forget('cart');

        return redirect(route('delivery.index', $host));
    }

    /**
     * Prepara o carrinho de compras
     * @param $host
     */
    private function prepareCart($host='delivery'){
        if (Session::has('cart')){
            $this->totalCart = formatBRL(Cart::subtotal());
            $this->cartView = view('delivery.partials.cart', compact('host'))->with([
                'cart' => Cart::content()->toArray(),
            ]);
        }
    }

    public function addOrder(DeliveryRequest $request,
                             Product $product, SharedOrderType $sharedOrderType,
                             SharedOrderPayment $sharedOrderPayment, SharedCurrency $sharedCurrency){

        $attributes = $request->all();
//        dd($attributes);

        //Adicionando a Ordem
        $addedOrder = $this->getAddedOrder($attributes);
//        dd($addedOrder->save());


        //Atributos da ordem
        $sharedOrderType->firstOrCreate(['tipo'=>'ordemVenda'])->orders()->save($addedOrder);
        $sharedOrderPayment->firstOrCreate(['pagamento'=>$attributes['pagamento']])->orders()->save($addedOrder);

        //Adicionando Status Aberto
        $this->syncStatus($addedOrder, [0=>'1']);

        // Adicionando Partner
        $addedPartner = $this->getAddedPartner($attributes);
        $addedPartner->orders()->save($addedOrder);
        $addedPartner->status()->sync([0=>SharedStat::where(['status'=>'ativado'])->first()->id]);
        $addedPartner->groups()->sync([0=>PartnerGroup::where(['grupo'=>'Cliente'])->first()->id]);

        //Adicionando o endereço
        $addedAddress = $this->getAddedAddress($attributes);
        $addedPartner->addresses()->save($addedAddress);
        $addedAddress->orders()->save($addedOrder);


        //Adicionando os contatos
        if (!empty($attributes['email'])) {
            $addedPartner->contacts()->save(
                Contact::firstOrCreate([
                    'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
                    'partner_id' => $addedPartner->id,
                    'contact_type' => 'email',
                    'contact_data' => $attributes['email']
                ]) );
        }
        if (!empty($attributes['telefone'])) {
            $addedPartner->contacts()->save(
                Contact::firstOrCreate([
                    'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
                    'partner_id' => $addedPartner->id,
                    'contact_type' => 'telefone',
                    'contact_data' => $attributes['telefone']
                ]) );
        }
        if (!empty($attributes['whatsapp'])) {
            $addedPartner->contacts()->save(
                Contact::firstOrCreate([
                    'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
                    'partner_id' => $addedPartner->id,
                    'contact_type' => 'whatsapp',
                    'contact_data' => $attributes['whatsapp']
                ]) );
        }


        //Adicionando os itens do pedido
        foreach ($attributes['quantidade'] as $key => $quantidade) {
            $productFound = $product->find($key);
            $addedItemOrder = $this->getAddedItemOrder($quantidade, $attributes['valor_unitario'][$key], $productFound->cost_id);

//            $costAllocate->firstOrCreate(['nome'=>'Mercadorias'])->itemOrders()->save($addedItemOrder);
            $sharedCurrency->firstOrCreate(['nome_universal'=>'BRL'])->itemOrders()->save($addedItemOrder);
            $addedOrder->orderItems()->save($addedItemOrder);
            $productFound->itemOrders()->save($addedItemOrder);
        }

        MessagesRepository::sendOrderCreated([
            'name'=>config('mail.from.name'),
            'email'=>config('mail.from.address'),
            'user'=>isset($addedPartner->user)?$addedPartner->user:null,
            'partner'=>$addedPartner,
            'order'=>$addedOrder,
        ]);

        $params = ['pedido' => $addedOrder->id, 'email' => ''];
        if (!is_null($email = $addedPartner->contacts()->where(['contact_type' => 'email'])->first()))
            $params['email'] = $email->contact_data;
        flash()->success(trans('delivery.flash.pedidoAdd', $params));
        if (Session::has('cart')) Session::forget('cart');
        return redirect(route('delivery.index'));

    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getAddedAddress(array $attributes)
    {
        if ( (Auth::guest()) || ($attributes['address_id']=='novo') ){
            $addressAttribute = [
                'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
                'cep' => $attributes['cep'],
                'logradouro' => $attributes['logradouro'],
                'numero' => $attributes['numero'],
            ];
            if (!empty($attributes['complemento'])) $addressAttribute['complemento'] = $attributes['complemento'];
            if (!empty($attributes['bairro'])) $addressAttribute['bairro'] = $attributes['bairro'];
            if (!empty($attributes['cidade'])) $addressAttribute['cidade'] = $attributes['cidade'];
            if (!empty($attributes['estado'])) $addressAttribute['estado'] = $attributes['estado'];
            if (!empty($attributes['observacao'])) $addressAttribute['obs'] = $attributes['observacao'];

//        return $address->create($addressAttribute);
            return new Address($addressAttribute);
        } else {
            return Address::find($attributes['address_id']);
        }
    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getAddedPartner($attributes)
    {
//        $partnerAttribute = [
//            'mandante' => 'teste',
//            'nome' => $attributes['nome'],
//        ];
//        if (!empty($attributes['data_nascimento'])) $partnerAttribute['data_nascimento'] = $attributes['data_nascimento'];
//        if (!empty($attributes['cpf'])) $partnerAttribute['cpf'] = $attributes['cpf'];
//        return (new Partner)->create($partnerAttribute);
//        return (new Partner)->getAddedPartner($attributes);
        if (Auth::guest()){
            $partnerAttribute = [
                'mandante' => config('app.mandante'),
                'nome' => $attributes['nome'],
            ];
            if (!empty($attributes['data_nascimento'])) $partnerAttribute['data_nascimento'] = $attributes['data_nascimento'];
            return Partner::create($partnerAttribute);
        } else {
            return Partner::firstByAttributes(['user_id' => Auth::user()->id]);
        }

    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getAddedOrder($attributes)
    {
        $orderAttribute = [
            'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
            'posted_at' => Carbon::now(),
            'currency_id' => SharedCurrency::where(['nome_universal'=>'BRL'])->first()->id,
//            'shared_order_type_id' => SharedOrderType::where(['tipo'=>'ordemVenda'])->first()->id,
//            'payment_id' => SharedOrderPayment::where(['pagamento'=>$attributes['pagamento']])->first()->id,
            'valor_total' => $attributes['total'],
        ];
        if (!empty($attributes['troco'])) $orderAttribute['troco'] = $attributes['troco'];
        return new Order($orderAttribute);
    }

    /**
     * @param $quantidade
     * @param $valor
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getAddedItemOrder($quantidade, $valor, $cost)
    {
        $itemOrderAttribute = [
            'mandante' => Auth::check()?Auth::user()->mandante:config('app.mandante'),
            'cost_id' => $cost,
            'quantidade' => $quantidade,
            'valor_unitario' => $valor,
        ];
        return new ItemOrder($itemOrderAttribute);
    }

    /**
     * Sync up a list of status in the database.
     *
     * @param Order $order
     * @param array $status
     */
    private function syncStatus(Order $order, $status) {
        $order->status()->sync(is_null($status)?[]:$status);
    }

}
