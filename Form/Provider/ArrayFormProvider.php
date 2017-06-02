<?php

namespace Eliophot\Bundle\DynamicFormBundle\Form\Provider;

use Eliophot\Bundle\DynamicFormBundle\Form\Provider\FormProviderInterface;

/**
 * Array Form Provider.
 */
class ArrayFormProvider implements FormProviderInterface
{
    private $formArray;

    private $formName;

    /**
     * @param array $formArray
     */
    public function setFormArray(array $formArray)
    {
        $this->formArray = $formArray;
    }

    /**
     * @return string
     */
    public function buildJson()
    {
        return json_encode($this->formArray);
    }

    /**
     * @param mixed $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    /**
     * @return mixed
     */
    public function getFormName()
    {
        return $this->formName;
    }
}
