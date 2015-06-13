<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Repositories\LangValidatorRepository;

class DeliveryRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

        return [
            'email' => 'required_without:telefone',
			'nome' => 'required',
			'cep' => 'required_if:address_id,novo',
			'logradouro' => 'required_if:address_id,novo',
			'numero' => 'required_if:address_id,novo',
//            'product_id0' => 'required_without_all:product_id1,product_id2,product_id3,product_id4,product_id5',
//            'cost_id0' => 'required_without_all:cost_id1,cost_id2,cost_id3,cost_id4,cost_id5',
//            'quantidade0' => 'numeric|required_without_all:quantidade1,quantidade2,quantidade3,quantidade4,quantidade5',
//            'valor_unitario0' => 'numeric|required_without_all:valor_unitario1,valor_unitario2,valor_unitario3,valor_unitario4,valor_unitario5',
		];
	}

    public function validator(\Illuminate\Validation\Factory $factory){
        return new LangValidatorRepository(
            $factory->getTranslator(),
            $this->all(),
            $this->container->call([$this, 'rules']),
            [
                'required_without'    => 'O campo :attribute ou :values deve ser preenchido.',
            ],
            [
                'email'=> trans('modelPartner.attributes.email'),
                'telefone'=> trans('modelPartner.attributes.telefone'),
                'nome'=> trans('modelPartner.attributes.nome'),
                'cep'=> trans('modelPartner.attributes.cep'),
                'logradouro'=> trans('modelPartner.attributes.logradouro'),
                'numero'=> trans('modelPartner.attributes.numero'),
//                'product_id0'=> trans('modelItemOrder.attributes.product_id'),
//                'cost_id0'=> trans('modelItemOrder.attributes.cost_id'),
//                'quantidade0'=> trans('modelItemOrder.attributes.quantidade'),
//                'valor_unitario0'=> trans('modelItemOrder.attributes.valor_unitario'),
            ],
            'Order');
    }
}
