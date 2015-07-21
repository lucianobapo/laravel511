<?php

namespace App\Http\Controllers\Erp;

use App\Models\CostAllocate;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class CostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function index($host, CostAllocate $costAllocate, Request $request)
    {
        $params = $request->all();
        $costAllocateOrdered = $costAllocate->sorting($params, 'numero', true);

        return view('erp.costs.index', compact('host','costAllocate'))->with([
            'method' => 'POST',
            'route' => 'costs.store',
            'costs' => $costAllocateOrdered->with('itemOrders')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
//            'grupos'=> PartnerGroup::lists('grupo','id'),
//            'group_selected' => null,
//            'status' => SharedStat::lists('descricao','id'),
//            'status_selected' => null,
            'submitButtonText' => trans('cost.actionAddBtn'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @return Response
     */
    public function edit($host, CostAllocate $costAllocate, Request $request){
        $params = $request->all();
        $costAllocateOrdered = $costAllocate->sorting($params, 'numero', true);

        return view('erp.costs.index', compact('host','costAllocate'))->with([
            'method' => 'PATCH',
            'route' => 'costs.update',
            'costs' => $costAllocateOrdered->with('itemOrders')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
//            'grupos'=> PartnerGroup::lists('grupo','id'),
//            'group_selected' => $partner->groups()->getRelatedIds()->toArray(),
//            'status'=> SharedStat::lists('descricao','id'),
//            'status_selected' => $partner->status()->getRelatedIds()->toArray(),
            'submitButtonText' => trans('cost.actionUpdateBtn'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CostAllocate $costAllocate
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store(CostAllocate $costAllocate, Request $request, $host)
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
    public function update($host, CostAllocate $costAllocate, Request $request){
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
    public function destroy($host, Request $request, CostAllocate $costAllocate)
    {
        if ($request->method()==='DELETE'){
            $costAllocate->delete();
            flash()->overlay(trans('cost.flash.deleted', ['nome' => $costAllocate->descricao]),trans('cost.flash.deletedTitle'));
            return redirect(route('costs.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }
}
