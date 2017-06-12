<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use ACSEO\Bundle\DynamicFormBundle\Events;
use ACSEO\Bundle\DynamicFormBundle\Event\FormEvent;
use ACSEO\Bundle\DynamicFormBundle\Form\Provider\FormProviderInterface;

/**
 * Class FormManager
 */
class FormManager implements FormManagerInterface
{
    protected $dispatcher;
    protected $formFactory;
    protected $dynamicFormType;

    /**
     * FormManager constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param FormFactoryInterface     $formFactory
     * @param mixed                    $dynamicFormType
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactoryInterface $formFactory, $dynamicFormType)
    {
        $this->dispatcher = $dispatcher;
        $this->formFactory = $formFactory;
        $this->dynamicFormType = $dynamicFormType;
    }

    /**
     * @param FormProviderInterface $formProvider
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(FormProviderInterface $formProvider)
    {
        $this->dynamicFormType->setFormStruct($formProvider->buildJson());
        $this->dynamicFormType->setFormName($formProvider->getFormName());

        $form = $this->formFactory->create(DynamicFormType::class);

        $event = new FormEvent($form);
        $this->dispatcher->dispatch(Events::FORM_CREATE, $event);

        return $form;
    }
}
