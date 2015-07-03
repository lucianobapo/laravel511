<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\SharedStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnersController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param Partner $partner
     * @param $host
     * @return Response
     */
    public function index(Partner $partner, Request $request, $host)
    {
        $params = $request->all();
        $partnerOrdered = $this->sorting($partner, $params);

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

    public function edit($host, Partner $partner, Request $request){
        $params = $request->all();
        $partnerOrdered = $this->sorting($partner, $params);

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
     * Sync up a list of groups in the database.
     *
     * @param Partner $partner
     * @param array $group
     */
    private function syncGroups(Partner $partner, $group)
    {
        $partner->groups()->sync(is_null($group)?[]:$group);
    }

    /**
     * Sync up a list of status in the database.
     *
     * @param Partner $partner
     * @param array $status
     */
    private function syncStatus(Partner $partner, $status)
    {
        $partner->status()->sync(is_null($status)?[]:$status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Partner $partner, PartnerRequest $request, $host)
    {
        $attributes = $request->all();
//        $attributes['mandante'] = Auth::user()->mandante;

        $newPartner = $partner->create($attributes);

        $this->syncItems($newPartner, $attributes);

        flash()->overlay(trans('partner.flash.created'),trans('partner.flash.createdTitle'));

        return redirect(route('partners.index', $host));
    }

    public function update($host, Partner $partner, PartnerRequest $request){
        $attributes = $request->all();

        $updatedPartner = $partner->update($attributes);

        $this->syncItems($partner, $attributes);

        flash()->overlay(trans('partner.flash.updated', ['nome' => $partner->nome]),trans('partner.flash.updatedTitle'));

        return redirect(route('partners.index', $host));
    }

    public function destroy(Request $request, $host, Partner $partner)
    {
        if ($request->method()==='DELETE'){
            $nome = $partner->nome;
            if ($partner->delete())
                flash()->overlay(trans('partner.flash.deleted', ['nome' => $nome]),trans('partner.flash.deletedTitle'));

            return redirect(route('partners.index', $host));
        }
    }

    /**
     * @param Partner $partner
     * @param $params
     * @return array
     */
    private function sorting(Partner $partner, &$params)
    {
        if (!isset($params['direction'])) $params['direction'] = false;
        if (isset($params['sortBy']))
            return $partner->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
        else
            return $partner->orderBy('nome', 'asc');
    }

    /**
     * @param Partner $partner
     * @param $attributes
     */
    private function syncItems(Partner $partner, $attributes)
    {
        //Adicionando Grupos
        if (empty($attributes['grupos'])) $this->syncGroups($partner, []);
        else $this->syncGroups($partner, $attributes['grupos']);

        //Adicionando Status
        if (empty($attributes['status'])) $this->syncStatus($partner, []);
        else $this->syncStatus($partner, $attributes['status']);
    }

}
