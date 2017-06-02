<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Eliophot\Bundle\DynamicFormBundle\Form;

use Eliophot\Bundle\DynamicFormBundle\Form\Provider\FormProviderInterface;

/**
 * Class FormManagerInterface
 */
interface FormManagerInterface
{
    /**
     * Creates an empty form instance.
     * @param FormProviderInterface $formProvider
     * @return mixed
     */
    public function createForm(FormProviderInterface $formProvider);
}
