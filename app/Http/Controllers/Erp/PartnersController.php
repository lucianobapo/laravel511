<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
    public function index(Partner $partner, Request $request, $host)
    {
        $params = $request->all();
        if ( !isset($params['direction']) ) $params['direction'] = false;
        if ( isset($params['sortBy']) ) $partner = $partner->orderBy($params['sortBy'], ($params['direction']?'asc':'desc') );
        else $partner = $partner->orderBy('nome', 'asc' );

        return view('erp.partners.index', compact('host'))->with([
//            'partners' => $partner->all(),
            'partners' => $partner->with('groups','status')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'grupos'=> PartnerGroup::lists('grupo','id'),
            'status' => SharedStat::lists('descricao','id'),
        ]);
    }

    /**
     * Sync up a list of groups in the database.
     *
     * @param Partner $partner
     * @param array $group
     */
    private function syncStatus(Partner $partner, $group)
    {
        $partner->groups()->sync(is_null($group)?[]:$group);
    }

}
