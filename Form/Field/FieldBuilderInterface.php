<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Field;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface FieldBuilderInterface
 */
interface FieldBuilderInterface
{
    /**
     * Add a field to dynamic form
     *
     * @param $name
     * @param string $field
     * @param array $options
     * @param FormBuilderInterface $builder
     * @return mixed
     */
    public function addField($name, $field, $options,FormBuilderInterface $builder);
}