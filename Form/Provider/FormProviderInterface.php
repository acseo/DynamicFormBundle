<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Eliophot\Bundle\DynamicFormBundle\Form\Provider;

interface FormProviderInterface
{
    /**
     * Generate json for form field
     */
    public function buildJson();
    
    /**
     * Define form name
     */
    public function getFormName();
}
