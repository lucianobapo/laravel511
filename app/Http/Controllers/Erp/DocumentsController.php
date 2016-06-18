<?php

namespace App\Http\Controllers\Erp;

use App\Models\Document;
use App\Models\Partner;
use App\Repositories\PartnerRepository;
use App\Repositories\WidgetsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Exception;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
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
     * @param $host
     * @param Document $document
     * @param Request $request
     * @param Partner $partner
     * @return Response
     */
    public function index(Document $document, Request $request, Partner $partner, $host=null)
    {
        return $this->getGrid('index', $host, $document, $request, $partner);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $host
     * @param Document $document
     * @param Request $request
     * @param Partner $partner
     * @return Response
     */
    public function edit(Document $document, Request $request, Partner $partner, $host=null){
        return $this->getGrid('edit', $host, $document, $request, $partner);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Document $document
     * @param Request $request
     * @param $host
     * @return Response
     */
    public function store(Document $document, Request $request, $host=null)
    {
        $attributes = $request->all();
        $document->create($attributes);
        flash()->overlay(trans('document.flash.created'),trans('document.flash.createdTitle'));
        return redirect(route('documents.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $host
     * @param Document $document
     * @param Request $request
     * @return Response
     */
    public function update(Document $document, Request $request, $host=null){
        $attributes = $request->all();
        $document->update($attributes);
        flash()->overlay(trans('document.flash.updated', ['nome' => $document->document_data]), trans('document.flash.updatedTitle'));
        return redirect(route('documents.index', [$host]+$request->only('direction','sortBy','page')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $host
     * @param Document $document
     * @return Response
     * @throws Exception
     */
    public function destroy(Document $document, Request $request, $host=null)
    {
        if ($request->method()==='DELETE'){
            $document->delete();
            flash()->overlay(trans('document.flash.deleted', ['nome' => $document->document_data]),trans('document.flash.deletedTitle'));
            return redirect(route('documents.index', [$host]+$request->only('direction','sortBy','page')));
        } else throw new Exception(trans('app.errors.method'));
    }

    /**
     * @param $tipo
     * @param $host
     * @param Document $document
     * @param Request $request
     * @param Partner $partner
     * @return $this
     */
    public function getGrid($tipo, &$host, Document &$document, Request &$request, Partner &$partner){
//        dd(array_keys(config('delivery.document_types')));
//        dd(array_map('strtoupper', config('delivery.document_types')));
//        dd([''=>'']+array_combine(config('delivery.document_types'),array_map('strtoupper', config('delivery.document_types'))));

//        dd($document->first()->document_type);
        return $this->widgetsRepository->showGrid($document, [
            'host' => $host,
            'method' => $tipo=='index'?'POST':'PATCH',
            'route' => [
                'form' => $tipo=='index'?'documents.store':'documents.update',
                'destroy' => 'documents.destroy',
                'edit' => 'documents.edit',
                'index' => 'documents.index',
            ],
            'modelTrans' => 'modelDocument.attributes.',
            'gridTitle' => trans('document.title'),
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
                    'selectList' => $this->partnerRepository->getCachedPartnersActivatedSelectList(),
                    'selectedItem' => $tipo=='index'?null:$partner->partner_id,
                ],
                [
                    'name' => 'document_type',
                    'column' => 'document_type_name',
                    'inputType' => 'select',
                    'selectList' => [''=>'']+config('delivery.document_types'),
                    'selectedItem' => $tipo=='index'?null:$document->document_type,
                ],
                [ 'name' => 'document_data', ],
            ],
        ], $request->all());
    }
}
