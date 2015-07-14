<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProductRequest;
use App\Models\CostAllocate;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use App\Repositories\ImageRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ProductsController extends Controller {

    private $orderRepository;
    private $imageRepository;

    public function __construct(OrderRepository $orderRepository, ImageRepository $imageRepository) {
//        $this->middleware('auth',['except'=> ['index']]);
//        $this->middleware('guest',['only'=> ['index','show']]);
        $this->orderRepository = $orderRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @param $host
     * @return Response
     */
    public function index(Product $product, Request $request, $host)
    {
        $params = $request->all();
        $productOrdered = $this->orderRepository->sorting($product, $params);
//        if ( !isset($params['direction']) ) $params['direction'] = false;
//        if ( isset($params['sortBy']) ) $product = $product->orderBy($params['sortBy'], ($params['direction']?'asc':'desc') );
//        else $product = $product->orderBy('nome', 'asc' );

        return view('erp.products.index', compact('host','product'))->with([
            'method' => 'POST',
            'route' => 'products.store',
            'products' => $productOrdered->with('groups','status','cost')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> ProductGroup::lists('grupo','id'),
            'group_selected' => null,
            'costs' => [''=>''] + CostAllocate::lists('descricao','id')->toArray(),
            'cost_selected' => null,
            'status'=> SharedStat::lists('descricao','id'),
            'status_selected' => null,
            'estoque' => $this->orderRepository->calculaEstoque()['estoque'],
            'submitButtonText' => trans('product.actionAddBtn'),
        ]);
    }

    public function edit($host, Product $product, Request $request){
        $params = $request->all();
        $productOrdered = $this->orderRepository->sorting($product, $params);

        return view('erp.products.index', compact('host','product'))->with([
            'method' => 'PATCH',
            'route' => 'products.update',
            'products' => $productOrdered->with('groups','status','cost')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> ProductGroup::lists('grupo','id'),
            'group_selected' => $product->groups()->getRelatedIds()->toArray(),
            'costs' => [''=>''] + CostAllocate::lists('descricao','id')->toArray(),
            'cost_selected' => $product->cost_id,
            'status'=> SharedStat::lists('descricao','id'),
            'status_selected' => $product->status()->getRelatedIds()->toArray(),
            'estoque' => $this->orderRepository->calculaEstoque()['estoque'],
            'submitButtonText' => trans('product.actionUpdateBtn'),
        ]);
    }

    public function update($host, Product $product, ProductRequest $request){
        $attributes = $request->all();

        if (!empty($attributes['imagem'])){
            $attributes['imagem'] = $this->imageRepository->updateImageFile($request,str_slug($product->nome),$product->imagem);
        }

        if(!isset($attributes['estoque'])) $attributes['estoque']=false;
        if(!isset($attributes['promocao'])) $attributes['promocao']=false;

        $updatedProduct = $product->update($attributes);

        $this->syncItems($product, $attributes);

        flash()->overlay(trans('product.flash.productUpdated', ['produto' => $product->id]),trans('product.flash.productUpdatedTitle'));

        return redirect(route('products.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Product $product, ProductRequest $request, $host)
    {
        $attributes = $request->all();
//        $attributes['mandante'] = Auth::user()->mandante;

        if (!empty($attributes['imagem'])){
            $attributes['imagem'] = $this->imageRepository->saveImageFile($request, str_slug($request->nome));
        }

        $newProduct = $product->create($attributes);

        $this->syncItems($newProduct, $attributes);

        flash()->overlay(trans('product.productCreated'),trans('product.productCreatedTitle'));

        return redirect(route('products.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param Product $product
     * @return Response
     * @throws \Exception
     */
    public function destroy(Request $request, $host, Product $product)
    {
        if ($request->method()==='DELETE'){
            $product->delete();
            flash()->overlay(trans('product.productDeleted'),trans('product.productDeletedTitle'));

            return redirect(route('products.index', $host));
        }
    }

    /**
     * Sync up a list of groups in the database.
     *
     * @param Product $product
     * @param array $group
     */
    private function syncGroups(Product $product, $group)
    {
        $product->groups()->sync(is_null($group)?[]:$group);
    }

    /**
     * Sync up a list of status in the database.
     *
     * @param Product $product
     * @param array $status
     */
    private function syncStatus(Product $product, $status)
    {
        $product->status()->sync(is_null($status)?[]:$status);
    }

    /**
     * @param Product $product
     * @param $attributes
     */
    private function syncItems(Product $product, $attributes)
    {
        //Adicionando Grupos
        if (empty($attributes['grupos'])) $this->syncGroups($product, []);
        else $this->syncGroups($product, $attributes['grupos']);

        //Adicionando Status
        if (empty($attributes['status'])) $this->syncStatus($product, []);
        else $this->syncStatus($product, $attributes['status']);
    }

}
