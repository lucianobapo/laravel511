<?php

namespace App\Http\Controllers\Erp;

use App\Models\Contact;
use App\Models\Document;
use App\Models\Partner;
use App\Repositories\WidgetsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class ContactsController extends Controller
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
     * @param $host
     * @param Contact $contact
     * @param Request $request
     * @param Partner $partner
     * @return Response
     */
    public function index($host, Contact $contact, Request $request, Partner $partner)
    {
        return $this->getGrid('index', $host, $contact, $request, $partner);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Contact $contact
     * @param Request $request
     * @param Partner $partner
     * @return Response
     */
    public function edit($host, Contact $contact, Request $request, Partner $partner){
        return $this->getGrid('edit', $host, $contact, $request, $partner);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Contact $contact
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store($host, Contact $contact, Request $request)
    {
        $attributes = $request->all();
        $contact->create($attributes);
        flash()->overlay(trans('contact.flash.created'),trans('contact.flash.createdTitle'));
        return redirect(route('contacts.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Contact $contact
     * @param Request $request
     * @return Response
     */
    public function update($host, Contact $contact, Request $request){
        $attributes = $request->all();
        $contact->update($attributes);
        flash()->overlay(trans('contact.flash.updated', ['nome' => $contact->contact_data]), trans('contact.flash.updatedTitle'));
        return redirect(route('contacts.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param Contact $contact
     * @return Response
     * @throws Exception
     */
    public function destroy($host, Contact $contact, Request $request)
    {
        if ($request->method()==='DELETE'){
            $contact->delete();
            flash()->overlay(trans('contact.flash.deleted', ['nome' => $contact->contact_data]),trans('contact.flash.deletedTitle'));
            return redirect(route('contacts.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    /**
     * @param $tipo
     * @param $host
     * @param Contact $contact
     * @param Request $request
     * @param Partner $partner
     * @return $this
     */
    public function getGrid($tipo, &$host, Contact &$contact, Request &$request, Partner &$partner){
        return $this->widgetsRepository->showGrid($contact, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?'contacts.store':'contacts.update',
                'destroy' => 'contacts.destroy',
                'edit' => 'contacts.edit',
                'index' => 'contacts.index',
            ],
            'modelTrans' => 'modelContact.attributes.',
            'gridTitle' => trans('contact.title'),
            'submitButtonText' => trans($tipo=='index'?'widget.grid.actionAddBtn':'widget.grid.actionUpdateBtn'),
            'with' => 'partner',
            'itemCount' => 20,
            'gridType' => 'content',
            'columns' =>[
                [ 'name' => 'id', 'inputDisabled' => true, ],
                [
                    'name' => 'partner_id',
                    'sub' => 'partner',
                    'column' => 'nome',
                    'inputType' => 'select',
                    'selectList' => $partner->partner_select_list,
                    'selectedItem' => $tipo=='index'?null:$partner->partner_id,
                ],
                [
                    'name' => 'contact_type',
                    'column' => 'contact_type_name',
                    'inputType' => 'select',
                    'selectList' => [''=>'']+config('delivery.contact_types'),
                    'selectedItem' => $tipo=='index'?null:$contact->contact_type,
                ],
                [ 'name' => 'contact_data', ],
            ],
        ], $request->all());
    }
}
