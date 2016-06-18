<?php

namespace App\Http\Controllers\Erp;

use App\Models\Address;
use App\Models\Partner;
use App\Repositories\PartnerRepository;
use App\Repositories\WidgetsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
    private $widgetsRepository;
    private $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository, WidgetsRepository $widgetsRepository) {
//        $this->middleware('auth',['except'=> ['index','show']]);
//        $this->middleware('guest',['only'=> ['index','show']]);
//        $this->middleware('after');
        $this->widgetsRepository = $widgetsRepository;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Address $address
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function index(Address $address, Request $request, Partner $partner, $host=null)
    {
        return $this->getGrid('index', $host, $address, $request, $partner);

//        $params = $request->all();
//        $addressOrdered = $address->sorting($params);
//
//        return view('erp.addresses.index', compact('host','address'))->with([
//            'method' => 'POST',
//            'route' => 'addresses.store',
//            'addresses' => $addressOrdered->with('partner')->paginate(10)->appends($params),
//            'params' => ['host'=>$host]+$params,
//            'submitButtonText' => trans('address.actionAddBtn'),
//            'partners' => $partner->partner_select_list,
//            'partner_selected' => null,
//        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Address $address
     * @param Request $request
     * @return Response
     */
    public function edit(Address $address, Request $request, Partner $partner, $host=null){
        return $this->getGrid('edit', $host, $address, $request, $partner);

//        $params = $request->all();
//        $addressOrdered = $address->sorting($params);
//
//        return view('erp.addresses.index', compact('host','address'))->with([
//            'method' => 'PATCH',
//            'route' => 'addresses.update',
//            'addresses' => $addressOrdered->with('partner')->paginate(10)->appends($params),
//            'params' => ['host'=>$host]+$params,
//            'submitButtonText' => trans('address.actionUpdateBtn'),
//            'partners' => $partner->partner_select_list,
//            'partner_selected' => $partner->partner_id,
//        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Address $address
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store(Address $address, Request $request, $host=null)
    {
        $attributes = $request->all();
        $address->create($attributes);
        flash()->overlay(trans('address.flash.created'),trans('address.flash.createdTitle'));
        return redirect(route('addresses.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Address $address
     * @param Request $request
     * @return Response
     */
    public function update($host, Address $address, Request $request){
        $attributes = $request->all();
        $address->update($attributes);
        flash()->overlay(trans('address.flash.updated', ['nome' => $address->endereco]), trans('address.flash.updatedTitle'));
        return redirect(route('addresses.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param Address $address
     * @return Response
     * @throws Exception
     */
    public function destroy(Address $address, Request $request, $host=null)
    {
        if ($request->method()==='DELETE'){
            $address->delete();
            flash()->overlay(trans('address.flash.deleted', ['nome' => $address->endereco]),trans('address.flash.deletedTitle'));
            return redirect(route('addresses.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    public function getGrid($tipo, $host=null, Address &$address, Request &$request, Partner &$partner){
        return $this->widgetsRepository->showGrid($address, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?'addresses.store':'addresses.update',
                'destroy' => 'addresses.destroy',
                'edit' => 'addresses.edit',
                'index' => 'addresses.index',
            ],
            'modelTrans' => 'modelAddress.attributes.',
            'gridTitle' => trans('address.title'),
            'submitButtonText' => trans($tipo=='index'?'widget.grid.actionAddBtn':'widget.grid.actionUpdateBtn'),
            'with' => 'partner',
            'itemCount' => 20,
            'gridType' => 'contentWide',
            'columns' =>[
                [ 'name' => 'id', 'inputDisabled' => true, ],
                [
                    'name' => 'partner_id',
                    'sub' => 'partner',
                    'column' => 'nome',
                    'inputType' => 'select',
                    'selectList' => $this->partnerRepository->getCachedPartnersActivatedSelectList(),
                    'selectedItem' => $tipo=='index'?null:$partner->partner_id,
                ],
                [
                    'name' => 'cep',
                    'thClass' => 'col-sm-1',
                    'attributes' => ['maxlength'=>8, 'class'=>'form-control numbersOnly cep', 'required'],
                ],
                [ 'name' => 'logradouro', 'thClass' => 'col-sm-2', ],
                [
                    'name' => 'numero',
                    'attributes' => ['size'=>5, 'class'=>'form-control', 'required'],
                ],
                [ 'name' => 'complemento', 'thClass' => 'col-sm-1', ],
                'bairro',
                'cidade',
                [ 'name' => 'estado', 'attributes' => ['size'=>5, 'class'=>'form-control'], ],
                [ 'name' => 'obs', 'thClass' => 'col-sm-2', ],
            ],
        ], $request->all());
    }
}
