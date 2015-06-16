<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Repositories\LangValidatorRepository;

class PartnerRequest extends Request {

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
			'nome' => 'required',
		];
	}

    public function validator(\Illuminate\Validation\Factory $factory){
        return new LangValidatorRepository(
            $factory->getTranslator(),
            $this->all(),
            $this->container->call([$this, 'rules']),
            [],
            [
                'nome'=> trans('modelPartner.attributes.nome'),
            ],
            'Partner');
    }
}
