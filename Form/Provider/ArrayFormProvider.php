<?php

namespace ACSEO\Bundle\DynamicFormBundle\Form\Provider;

use ACSEO\Bundle\DynamicFormBundle\Form\Provider\FormProviderInterface;

/**
 * Array Form Provider.
 */
class ArrayFormProvider implements FormProviderInterface
{
    private $formArray;

    private $formName;

    public function setFormArray(array $formArray)
    {
        $this->formArray = $formArray;
    }

    public function buildJson()
    {
        return json_encode($this->formArray);
    }
    
    public function setFormName($formName) {
        $this->formName = $formName;
    }
    
    public function getFormName() {
        return $this->formName;
    }
}