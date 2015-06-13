<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Repositories\LangValidatorRepository;

class OrderRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
//        $status = $this->route('orders')->status->toArray();
//        foreach ($status as $stat) {
//
//            if ($stat['status']=='finalizado') {
//                dd($stat['status']);
//                return false;
//
//            }
//        }
//        dd(!count($this->route('orders')->status()->where('status', 'finalizado')->get()));
        if(is_null($this->route('orders'))) return true;
        return !count($this->route('orders')->status()->where('status', 'finalizado')->get());
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

        return [
            'partner_id' => 'required',
            'address_id' => 'required',
			'posted_at' => 'required|date',
            'product_id0' => 'required_without_all:product_id1,product_id2,product_id3,product_id4,product_id5',
            'cost_id0' => 'required_without_all:cost_id1,cost_id2,cost_id3,cost_id4,cost_id5',
            'quantidade0' => 'numeric|required_without_all:quantidade1,quantidade2,quantidade3,quantidade4,quantidade5',
            'valor_unitario0' => 'numeric|required_without_all:valor_unitario1,valor_unitario2,valor_unitario3,valor_unitario4,valor_unitario5',
		];
	}

    public function validator(\Illuminate\Validation\Factory $factory){
        return new LangValidatorRepository(
            $factory->getTranslator(),
            $this->all(),
            $this->container->call([$this, 'rules']),
            array(),
            [
                'partner_id'=> trans('modelOrder.attributes.partner_id'),
                'address_id'=> trans('modelOrder.attributes.address_id'),
                'posted_at'=> trans('modelOrder.attributes.posted_at'),
                'product_id0'=> trans('modelItemOrder.attributes.product_id'),
                'cost_id0'=> trans('modelItemOrder.attributes.cost_id'),
                'quantidade0'=> trans('modelItemOrder.attributes.quantidade'),
                'valor_unitario0'=> trans('modelItemOrder.attributes.valor_unitario'),
            ],
            'Order');
    }
}
