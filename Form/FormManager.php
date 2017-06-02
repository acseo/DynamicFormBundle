<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Eliophot\Bundle\DynamicFormBundle\Form;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Eliophot\Bundle\DynamicFormBundle\Events;
use Eliophot\Bundle\DynamicFormBundle\Event\FormEvent;
use Eliophot\Bundle\DynamicFormBundle\Form\Provider\FormProviderInterface;

/**
 * Class FormManager
 */
class FormManager implements FormManagerInterface
{
    /** @var EventDispatcherInterface $dispatcher */
    protected $dispatcher;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var mixed $dynamicFormType */
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
