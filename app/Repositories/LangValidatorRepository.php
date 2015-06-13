<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 14/04/15
 * Time: 02:30
 */

namespace App\Repositories;

use Illuminate\Validation\Validator;
use Symfony\Component\Translation\TranslatorInterface;

class LangValidatorRepository extends Validator{

    private $validationModel=null;

    /**
     * Override - Create a new Validator instance.
     *
     * @param null $validationModel
     * @param TranslatorInterface|\Symfony\Component\Translation\TranslatorInterface $translator
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     */
    public function __construct(TranslatorInterface $translator, array $data, array $rules, array $messages = array(), array $customAttributes = array(), $validationModel=null)
    {
        $this->translator = $translator;
        $this->customMessages = $messages;
        $this->data = $this->parseData($data);
        $this->rules = $this->explodeRules($rules);
        $this->customAttributes = $customAttributes;
        $this->validationModel = $validationModel;
    }

    /**
     * @return null
     */
    public function getValidationModel()
    {
        return $this->validationModel;
    }

    /**
     * Override - Get the displayable name of the attribute.
     *
     * @param  string  $attribute
     * @return string
     */
    protected function getAttribute($attribute)
    {
        // The developer may dynamically specify the array of custom attributes
        // on this Validator instance. If the attribute exists in this array
        // it takes precedence over all other ways we can pull attributes.
        if (isset($this->customAttributes[$attribute]))
        {
            return $this->customAttributes[$attribute];
        }

        if (is_null($this->validationModel))
            $key = "validation.attributes.{$attribute}";
        else
            $key = $this->validationModel.".attributes.{$attribute}";

        // We allow for the developer to specify language lines for each of the
        // attributes allowing for more displayable counterparts of each of
        // the attributes. This provides the ability for simple formats.
        if (($line = $this->translator->trans($key)) !== $key)
        {
            return $line;
        }

        // If no language line has been specified for the attribute all of the
        // underscores are removed from the attribute name and that will be
        // used as default versions of the attribute's displayable names.
        return str_replace('_', ' ', snake_case($attribute));
    }
}