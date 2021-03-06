<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Models\User;
use App\Repositories\WidgetsRepository;
use Exception;
use App\Http\Controllers\Controller;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\SharedStat;
use Illuminate\Http\Request;

class PartnersController extends Controller {

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
    public function index(Partner $partner, Request $request, User $user, $host=null)
    {
        return $this->getGrid('index', $host, $partner, $request, $user);
//        $params = $request->all();
//        $partnerOrdered = $partner->sorting($params);
//
//        return view('erp.partners.index', compact('host','partner'))->with([
//            'method' => 'POST',
//            'route' => 'partners.store',
//            'partners' => $partnerOrdered->with('groups','status')->paginate(10)->appends($params),
//            'params' => ['host'=>$host]+$params,
//            'grupos'=> PartnerGroup::lists('grupo','id'),
//            'group_selected' => null,
//            'status' => SharedStat::lists('descricao','id'),
//            'status_selected' => null,
//            'submitButtonText' => trans('partner.actionAddBtn'),
//        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Partner $partner
     * @param Request $request
     * @return Response
     */
    public function edit(Partner $partner, Request $request, User $user, $host=null){
        return $this->getGrid('edit', $host, $partner, $request, $user);
//        $params = $request->all();
//        $partnerOrdered = $partner->sorting($params);
//
//        return view('erp.partners.index', compact('host','partner'))->with([
//            'method' => 'PATCH',
//            'route' => 'partners.update',
//            'partners' => $partnerOrdered->with('groups','status')->paginate(10)->appends($params),
//            'params' => ['host'=>$host]+$params,
//            'grupos'=> PartnerGroup::lists('grupo','id'),
//            'group_selected' => $partner->groups()->getRelatedIds()->toArray(),
//            'status'=> SharedStat::lists('descricao','id'),
//            'status_selected' => $partner->status()->getRelatedIds()->toArray(),
//            'submitButtonText' => trans('partner.actionUpdateBtn'),
//        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Partner $partner, PartnerRequest $request, $host=null)
    {
        $attributes = $request->all();
        $partnerCreated = $partner->create($attributes);
        $partnerCreated->syncItems($attributes);
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
    public function update(Partner $partner, PartnerRequest $request, $host=null){
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
    public function destroy(Request $request, Partner $partner, $host=null)
    {
        if ($request->method()==='DELETE'){
            $nome = $partner->nome;
            if ($partner->delete())
                flash()->overlay(trans('partner.flash.deleted', ['nome' => $nome]),trans('partner.flash.deletedTitle'));
            return redirect(route('partners.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    public function getGrid($tipo, $host=null, Partner &$partner, Request &$request, User &$user){
        return $this->widgetsRepository->showGrid($partner, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?'partners.store':'partners.update',
                'destroy' => 'partners.destroy',
                'edit' => 'partners.edit',
                'index' => 'partners.index',
            ],
            'modelTrans' => 'modelPartner.attributes.',
            'gridTitle' => trans('partner.title'),
            'submitButtonText' => trans($tipo=='index'?'widget.grid.actionAddBtn':'widget.grid.actionUpdateBtn'),
            'with' => ['groups','status','user'],
            'itemCount' => 20,
            'gridType' => 'contentWide',
//            'sortColumn' => 'numero',
//            'sortDirection' => true,
            'columns' =>[
                [ 'name' => 'id', 'inputDisabled' => true, ],
                [
                    'name' => 'user_id',
                    'thClass' => 'col-sm-3',
                    'sub' => 'user',
                    'column' => 'user_provider',
                    'inputType' => 'select',
                    'selectList' => $user->user_select_list,
                    'selectedItem' => $tipo=='index'?null:$user->user_id,
                    'attributes' => ['class'=>'form-control', 'select2'=>trans('widget.grid.selecione',['valor'=>trans('modelPartner.attributes.user_id')])],
                ],
                [ 'name' => 'nome', 'thClass' => 'col-sm-3', 'attributes' => ['class'=>'form-control', 'required'], ],
                [ 'name' => 'data_nascimento', 'thClass' => 'col-sm-1', 'inputType' => 'date', 'inputDefault' => 'data_nascimento_for_field', ],
                [ 'name' => 'observacao', ],
                [
                    'name' => 'grupos[]',
                    'thClass' => 'col-sm-1',
//                    'sub' => 'partner',
                    'column' => 'group_list',
                    'inputType' => 'select',
                    'selectList' => PartnerGroup::lists('grupo','id'),
                    'selectedItem' => $tipo=='index'?null:$partner->groups()->getRelatedIds()->toArray(),
                    'attributes' => ['class'=>'form-control select2tag', 'multiple'],
                    'sort' => false,
                    'customTitle' => 'modelPartner.attributes.grupos',
                ],
                [
                    'name' => 'status[]',
                    'thClass' => 'col-sm-1',
//                    'sub' => 'partner',
                    'column' => 'status_list',
                    'inputType' => 'select',
                    'selectList' => SharedStat::lists('descricao','id'),
                    'selectedItem' => $tipo=='index'?null:$partner->status()->getRelatedIds()->toArray(),
                    'attributes' => ['class'=>'form-control select2tag', 'multiple'],
                    'sort' => false,
                    'customTitle' => 'modelPartner.attributes.status',
                ],

            ],
        ], $request->all());
    }
}
