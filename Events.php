<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Eliophot\Bundle\DynamicFormBundle;

/**
 * Class Events
 */
final class Events
{

    /**
     * This event occurs when a new form is created
     *
     * The listener receives a Eliophot\Bundle\DynamicFormBundle\Event\FormEvent
     *
     * @var string
     */
    const FORM_CREATE = 'eliophot_dynamic_form.form.create';
}
