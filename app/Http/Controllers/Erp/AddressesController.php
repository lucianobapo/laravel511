<?php

namespace App\Http\Controllers\Erp;

use App\Models\Address;
use App\Models\Partner;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Address $address
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function index($host, Address $address, Request $request, Partner $partner)
    {
        $params = $request->all();
        $addressOrdered = $address->sorting($params);

        return view('erp.addresses.index', compact('host','address'))->with([
            'method' => 'POST',
            'route' => 'addresses.store',
            'addresses' => $addressOrdered->with('partner')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'submitButtonText' => trans('address.actionAddBtn'),
            'partners' => $partner->partner_select_list,
            'partner_selected' => null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Address $address
     * @param Request $request
     * @return Response
     */
    public function edit($host, Address $address, Request $request, Partner $partner){
        $params = $request->all();
        $addressOrdered = $address->sorting($params);

        return view('erp.addresses.index', compact('host','address'))->with([
            'method' => 'PATCH',
            'route' => 'addresses.update',
            'addresses' => $addressOrdered->with('partner')->paginate(10)->appends($params),
            'params' => ['host'=>$host]+$params,
            'submitButtonText' => trans('address.actionUpdateBtn'),
            'partners' => $partner->partner_select_list,
            'partner_selected' => $partner->partner_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Address $address
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store($host, Address $address, Request $request)
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
    public function destroy($host, Address $address, Request $request)
    {
        if ($request->method()==='DELETE'){
            $address->delete();
            flash()->overlay(trans('address.flash.deleted', ['nome' => $address->endereco]),trans('address.flash.deletedTitle'));
            return redirect(route('addresses.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }
}
