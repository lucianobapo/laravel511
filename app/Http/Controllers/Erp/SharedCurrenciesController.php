<?php namespace App\Http\Controllers\Erp;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\SharedCurrency;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository as CacheRepository;

class SharedCurrenciesController extends Controller {

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * Create a new sharedCurrencies controller instance.
     * @param CacheRepository $cache
     */
    public function __construct(CacheRepository $cache) {
//        $this->middleware('auth');
//        $this->middleware('auth',['except'=> ['index','show']]);
//        $this->middleware('guest',['only'=> ['index','show']]);

        $this->cache = $cache;
    }

    /**
     * Display a listing of the resource.
     *
     * @param SharedCurrency $sharedCurrency
     * @param Request $request
     * @return Response
     */
	public function index(SharedCurrency $sharedCurrency, Request $request, $host)
	{
        $params = $request->all();
        if ( !isset($params['direction']) ) $params['direction'] = false;
        if ( isset($params['sortBy']) ) $sharedCurrency = $sharedCurrency->orderBy($params['sortBy'], ($params['direction']?'asc':'desc') );
        $sharedCurrencies = $sharedCurrency->paginate(5)->appends($params);
//        $sharedCurrencies = $sharedCurrency->paginate(5);
//        $sharedCurrencies = $sharedCurrency->all();
        return view('sharedCurrencies.index',compact('sharedCurrencies', 'params', 'host'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
