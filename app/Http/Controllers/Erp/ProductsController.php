<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ProductRequest;
use App\Models\CostAllocate;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ProductsController extends Controller {

    public function __construct() {
//        $this->middleware('auth',['except'=> ['index']]);
//        $this->middleware('guest',['only'=> ['index','show']]);

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
        if ( !isset($params['direction']) ) $params['direction'] = false;
        if ( isset($params['sortBy']) ) $product = $product->orderBy($params['sortBy'], ($params['direction']?'asc':'desc') );
        else $product = $product->orderBy('nome', 'asc' );
        $product = $product->with('groups','status');

        return view('erp.products.index', compact('host'))->with([
            'products' => $product->with('groups','status','cost')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> ProductGroup::lists('grupo','id'),
            'costs' => [''=>''] + CostAllocate::lists('nome','id')->toArray(),
            'status'=> SharedStat::lists('descricao','id'),
            'estoque' => $this->calculaEstoque(),
        ]);
    }

    public function edit($host, Product $product){
//        dd($product);
        $params['direction'] = false;

        return view('erp.products.index', compact('host'))->with([
            'products' => $product->with('groups','status','cost')->paginate(10),
            'params' => ['host'=>$host]+$params,
            'grupos'=> ProductGroup::lists('grupo','id'),
            'costs' => [''=>''] + CostAllocate::lists('nome','id')->toArray(),
            'status'=> SharedStat::lists('descricao','id'),
            'estoque' => $this->calculaEstoque(),
        ]);
    }

    public function update(Product $product){
        dd($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Product $product, ProductRequest $request, $host)
    {
        // doing the validation, passing post data, rules and the messages
        $uploadedFile = $request->file('imagem');
        $clientOriginalName = 'imagem-de-'.str_slug(substr($uploadedFile->getClientOriginalName(),0,-4)).'.'.$uploadedFile->getClientOriginalExtension();
        // checking file is valid.
        if ($uploadedFile->isValid()) {
            $imageDir = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($imageDir)) Storage::makeDirectory($imageDir);
            Storage::put($imageDir . $clientOriginalName, file_get_contents($uploadedFile));
        } else {
            dd($clientOriginalName);
//                // sending back with error message.
//                Session::flash('error', 'uploaded file is not valid');
//                return redirect(route('products.index', $host));
        }
        $attributes = $request->all();
        $attributes['mandante'] = Auth::user()->mandante;
        $attributes['imagem'] = $clientOriginalName;
        $newProduct = $product->create($attributes);

        //Adicionando Grupos
        $this->syncGroups($newProduct, $attributes['grupos']);

        //Adicionando Status
        $this->syncStatus($newProduct, $attributes['status']);

        flash()->overlay(trans('product.productCreated'),trans('product.productCreatedTitle'));

        return redirect(route('products.index', $host));
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
//            dd($product->id);
//            dd($product->id);
//            Product::find($id)->delete();
//            dd($request->method());
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

    private function calculaEstoque()
    {
        $saldo_produtos = [];
        foreach (SharedOrderType::where(['tipo' => 'ordemVenda'])->first()->orders()->with('orderItems','orderItems.product')->get() as $ordem) {
            foreach ($ordem->orderItems as $item) {

                if (!$item->product->estoque) continue;
                if (isset($saldo_produtos[$item->product_id]))
                    $saldo_produtos[$item->product_id] = $saldo_produtos[$item->product_id] - $item->quantidade;
                else
                    $saldo_produtos[$item->product_id] = -$item->quantidade;
            }
        }
        foreach (SharedOrderType::where(['tipo' => 'ordemCompra'])->first()->orders()->with('orderItems','orderItems.product')->get() as $ordem) {
            foreach ($ordem->orderItems as $item) {
                if (!$item->product->estoque) continue;
                if (isset($saldo_produtos[$item->product_id]))
                    $saldo_produtos[$item->product_id] = $saldo_produtos[$item->product_id] + $item->quantidade;
                else
                    $saldo_produtos[$item->product_id] = +$item->quantidade;
            }
        }
        return $saldo_produtos;
    }

}
