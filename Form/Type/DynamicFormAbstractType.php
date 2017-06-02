<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class DynamicFormAbstractType
 */
abstract class DynamicFormAbstractType extends AbstractType
{
    protected $formStruct;
    
    protected $formName;

    public function setFormStruct($formStruct)
    {
        $this->formStruct = $formStruct;
    }
    
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

}