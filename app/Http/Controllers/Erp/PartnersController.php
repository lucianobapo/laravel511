<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\SharedStat;
use Illuminate\Http\Request;

class PartnersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param Partner $partner
     * @param $host
     * @return Response
     */
    public function index($host, Partner $partner, Request $request)
    {
        $params = $request->all();
        $partnerOrdered = $partner->sorting($params);

        return view('erp.partners.index', compact('host','partner'))->with([
            'method' => 'POST',
            'route' => 'partners.store',
            'partners' => $partnerOrdered->with('groups','status')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> PartnerGroup::lists('grupo','id'),
            'group_selected' => null,
            'status' => SharedStat::lists('descricao','id'),
            'status_selected' => null,
            'submitButtonText' => trans('partner.actionAddBtn'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Partner $partner
     * @param Request $request
     * @return Response
     */
    public function edit($host, Partner $partner, Request $request){
        $params = $request->all();
        $partnerOrdered = $partner->sorting($params);

        return view('erp.partners.index', compact('host','partner'))->with([
            'method' => 'PATCH',
            'route' => 'partners.update',
            'partners' => $partnerOrdered->with('groups','status')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> PartnerGroup::lists('grupo','id'),
            'group_selected' => $partner->groups()->getRelatedIds()->toArray(),
            'status'=> SharedStat::lists('descricao','id'),
            'status_selected' => $partner->status()->getRelatedIds()->toArray(),
            'submitButtonText' => trans('partner.actionUpdateBtn'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Partner $partner, PartnerRequest $request, $host)
    {
        $attributes = $request->all();

        $partner->create($attributes);

        $partner->syncItems($attributes);

        flash()->overlay(trans('partner.flash.created'),trans('partner.flash.createdTitle'));

        return redirect(route('partners.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Partner $partner
     * @param PartnerRequest $request
     * @return Response
     */
    public function update($host, Partner $partner, PartnerRequest $request){
        $attributes = $request->all();

        $partner->update($attributes);

        $partner->syncItems($attributes);

        flash()->overlay(trans('partner.flash.updated', ['nome' => $partner->nome]),trans('partner.flash.updatedTitle'));

        return redirect(route('partners.index', [$host]+$request->only('direction','sortBy','page')));
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
    public function destroy(Request $request, $host, Partner $partner)
    {
        if ($request->method()==='DELETE'){
            $nome = $partner->nome;
            if ($partner->delete())
                flash()->overlay(trans('partner.flash.deleted', ['nome' => $nome]),trans('partner.flash.deletedTitle'));

            return redirect(route('partners.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }
}
