<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Requests\ProductGroupRequest;
use App\Models\ProductGroup;
use App\Models\User;
use App\Repositories\WidgetsRepository;
use Exception;
use App\Http\Controllers\Controller;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\SharedStat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductGroupsController extends Controller {

    private $widgetsRepository;

    public function __construct(WidgetsRepository $widgetsRepository) {
//        $this->middleware('auth',['except'=> ['index','show']]);
//        $this->middleware('guest',['only'=> ['index','show']]);
//        $this->middleware('after');
        $this->widgetsRepository = $widgetsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Partner $partner
     * @param $host
     * @return Response
     */
    public function index(ProductGroup $model, Request $request, User $user, $host=null)
    {
        return $this->getGrid('index', $host, $model, $request, 'productGroups');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Partner $partner
     * @param Request $request
     * @return Response
     */
    public function edit(ProductGroup $model, Request $request, User $user, $host=null){
        return $this->getGrid('edit', $host, $model, $request, 'productGroups');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ProductGroup $model, ProductGroupRequest $request, $host=null)
    {
        $attributes = $request->all();
        $partnerCreated = $model->create($attributes);
        $partnerCreated->syncItems($attributes);
        flash()->overlay(trans('productGroups.flash.created'),trans('productGroups.flash.createdTitle'));
        return redirect(route('productGroups.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Partner $partner
     * @param PartnerRequest $request
     * @return Response
     */
    public function update(ProductGroup $model, ProductGroupRequest $request, $host=null){
        $attributes = $request->all();
        $model->update($attributes);
        $model->syncItems($attributes);
        flash()->overlay(trans('productGroups.flash.updated', ['nome' => $model->nome]),trans('productGroups.flash.updatedTitle'));
        return redirect(route('productGroups.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param Partner $partner
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, ProductGroup $model, $host=null)
    {
        if ($request->method()==='DELETE'){
            $nome = $model->nome;
            if ($model->delete())
                flash()->overlay(trans('productGroups.flash.deleted', ['nome' => $nome]),trans('productGroups.flash.deletedTitle'));
            return redirect(route('productGroups.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    public function getGrid($tipo, $host=null, Model &$model, Request &$request, $routePrefix){
        return $this->widgetsRepository->showGrid($model, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?$routePrefix.'.store':$routePrefix.'.update',
                'destroy' => $routePrefix.'.destroy',
                'edit' => $routePrefix.'.edit',
                'index' => $routePrefix.'.index',
            ],
            'modelTrans' => 'modelProductGroup.attributes.',
            'gridTitle' => trans($routePrefix.'.title'),
            'submitButtonText' => trans($tipo=='index'?'widget.grid.actionAddBtn':'widget.grid.actionUpdateBtn'),
            'with' => ['status'],
            'itemCount' => 20,
            'gridType' => 'content',
//            'sortColumn' => 'numero',
//            'sortDirection' => true,
            'columns' =>[
                [ 'name' => 'id', 'inputDisabled' => true, ],

                [ 'name' => 'grupo', 'thClass' => 'col-sm-5', 'attributes' => ['class'=>'form-control', 'required'], ],

                [
                    'name' => 'status[]',
                    'thClass' => 'col-sm-3',
//                    'sub' => 'partner',
                    'column' => 'status_list',
                    'inputType' => 'select',
                    'selectList' => SharedStat::lists('descricao','id'),
                    'selectedItem' => $tipo=='index'?null:$model->status()->getRelatedIds()->toArray(),
                    'attributes' => ['class'=>'form-control select2tag', 'multiple'],
                    'sort' => false,
                    'customTitle' => 'modelPartner.attributes.status',
                ],

            ],
        ], $request->all());
    }
}
