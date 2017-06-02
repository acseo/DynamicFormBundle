<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Type;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use ACSEO\Bundle\DynamicFormBundle\Form\Validator\ValidatorBuilderInterface;
use ACSEO\Bundle\DynamicFormBundle\Form\Field\FieldBuilderInterface;

/**
 * DynamicFormType
 */
class DynamicFormType extends DynamicFormAbstractType
{
    /** @var ValidatorBuilderInterface $validatorBuilder */
    private $validatorBuilder;

    /** @var FieldBuilderInterface $fieldBuilder */
    private $fieldBuilder;

    /**
     * DynamicFormType constructor.
     * @param ValidatorBuilderInterface $validatorBuilder
     * @param FieldBuilderInterface     $fieldBuilder
     */
    public function __construct(ValidatorBuilderInterface $validatorBuilder, FieldBuilderInterface $fieldBuilder)
    {
        $this->validatorBuilder = $validatorBuilder;
        $this->fieldBuilder = $fieldBuilder;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFields = json_decode($this->formStruct);

        if (!count($formFields)) {
            throw new Exception('FormStruc is empty');
        }

        foreach ($formFields as $name => $field) {
            $options = json_decode(json_encode($field->options), true);
            $constraints = $this->validatorBuilder->buildConstraints($field);

            if ($constraints) {
                $options = array_merge($options, array(
                    'constraints' => $constraints,
                ));
            }

            $this->fieldBuilder->addField($name, $field, $options, $builder);
        }

        $this->fieldBuilder->addMultipleFieldPostBindEvent($formFields, $builder);
        $this->fieldBuilder->alterDataPreSetDataEvent($formFields, $builder);
        $this->fieldBuilder->addAssociatedFielPostBindEvent($formFields, $builder);
    }

    /**
     * @return mixed
     */
    public function getBlockPrefix()
    {
        return $this->formName;
    }
}
