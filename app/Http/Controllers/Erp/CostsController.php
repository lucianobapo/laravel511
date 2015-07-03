<?php

namespace App\Http\Controllers\Erp;

use App\Models\CostAllocate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CostAllocate $costAllocate
     * @param $host
     * @return Response
     */
    public function index(CostAllocate $costAllocate, Request $request, $host)
    {
        $params = $request->all();
        $costAllocateOrdered = $this->sorting($costAllocate, $params);

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
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CostAllocate $costAllocate, Request $request, $host)
    {
        $attributes = $request->all();

        $costAllocate->create($attributes);

        flash()->overlay(trans('cost.flash.created'),trans('cost.flash.createdTitle'));

        return redirect(route('costs.index', $host));
    }


    /**
     * Grid table Sorting data
     *
     * @param CostAllocate $costAllocate
     * @param $params
     * @return array
     * @internal param CostAllocate $partner
     */
    private function sorting(CostAllocate $costAllocate, &$params)
    {
        if (!isset($params['direction'])) $params['direction'] = false;
        if (isset($params['sortBy']))
            return $costAllocate->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
        else
            return $costAllocate->orderBy('numero', 'asc');
    }
}
