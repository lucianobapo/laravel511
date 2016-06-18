<?php

namespace App\Http\Controllers\Erp;

use App\Models\CostAllocate;
use App\Repositories\WidgetsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class CostsController extends Controller
{
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
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function index(CostAllocate $costAllocate, Request $request, $host=null)
    {
        return $this->getGrid('index', $host, $costAllocate, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @return Response
     */
    public function edit(CostAllocate $costAllocate, Request $request, $host=null){
        return $this->getGrid('edit', $host, $costAllocate, $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store(CostAllocate $costAllocate, Request $request, $host=null)
    {
        $attributes = $request->all();

        $costAllocate->create($attributes);

        flash()->overlay(trans('cost.flash.created'),trans('cost.flash.createdTitle'));

        return redirect(route('costs.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @return Response
     */
    public function update(CostAllocate $costAllocate, Request $request, $host=null){
        $attributes = $request->all();

        $costAllocate->update($attributes);

        flash()->overlay(trans('cost.flash.updated', ['nome' => $costAllocate->descricao]),trans('cost.flash.updatedTitle'));

        return redirect(route('costs.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param CostAllocate $costAllocate
     * @return Response
     * @throws Exception
     */
    public function destroy(Request $request, CostAllocate $costAllocate, $host=null)
    {
        if ($request->method()==='DELETE'){
            $costAllocate->delete();
            flash()->overlay(trans('cost.flash.deleted', ['nome' => $costAllocate->descricao]),trans('cost.flash.deletedTitle'));
            return redirect(route('costs.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    public function getGrid($tipo, $host=null, CostAllocate &$costAllocate, Request &$request){
        return $this->widgetsRepository->showGrid($costAllocate, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?'costs.store':'costs.update',
                'destroy' => 'costs.destroy',
                'edit' => 'costs.edit',
                'index' => 'costs.index',
            ],
            'modelTrans' => 'modelCostAllocate.attributes.',
            'gridTitle' => trans('cost.title'),
            'submitButtonText' => trans($tipo=='index'?'widget.grid.actionAddBtn':'widget.grid.actionUpdateBtn'),
            'with' => 'itemOrders',
            'itemCount' => 20,
            'gridType' => 'content',
            'sortColumn' => 'numero',
            'sortDirection' => true,
            'columns' =>[
                [ 'name' => 'id', 'inputDisabled' => true, ],
                [ 'name' => 'nome', 'attributes' => ['class'=>'form-control', 'required'], ],
                [ 'name' => 'numero', 'attributes' => ['class'=>'form-control', 'required'], ],
                [ 'name' => 'descricao', 'attributes' => ['class'=>'form-control', 'required'], ],
            ],
        ], $request->all());
    }
}
