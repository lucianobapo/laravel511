<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Repositories\LangValidatorRepository;

class ProductRequest extends Request {

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
//            'imagem' => 'required_if:method,POST|mimes:png',
            'imagem' => 'mimes:jpeg,bmp,png',
			'nome' => 'required|min:3',
			'cost_id' => 'required',
            'valorUnitVenda' => 'numeric',
            'valorUnitVendaPromocao' => 'numeric',
            'valorUnitCompra' => 'numeric',
            'promocao' => 'boolean',
            'estoque' => 'boolean',
		];
	}

    public function validator(\Illuminate\Validation\Factory $factory){
        return new LangValidatorRepository(
            $factory->getTranslator(),
            $this->all(),
            $this->container->call([$this, 'rules']),
            [
                "required_if"      => "O campo :attribute deve ser preenchido.",
            ],
            [
                'imagem'=> trans('modelProduct.attributes.imagem'),
                'nome'=> trans('modelProduct.attributes.nome'),
                'cost_id'=> trans('modelProduct.attributes.costId'),
                'valorUnitVenda'=> trans('modelProduct.attributes.valorUnitVenda'),
                'valorUnitVendaPromocao'=> trans('modelProduct.attributes.valorUnitVendaPromocao'),
                'valorUnitCompra'=> trans('modelProduct.attributes.valorUnitCompra'),
                'promocao'=> trans('modelProduct.attributes.promocao'),
            ],
            'Product');
    }
}
