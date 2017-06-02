<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Validator;

/**
 * Interface ValidatorBuilderInterface
 */
interface ValidatorBuilderInterface
{
    /**
     * Generate validators for form field
     *
     * @param $field
     * @return array|bool
     */
    public function buildConstraints($field);
}