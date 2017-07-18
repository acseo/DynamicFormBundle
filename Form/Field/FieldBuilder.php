<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Field;

use ACSEO\Bundle\DynamicFormBundle\Form\Type\VirtualType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

/**
 * Builder
 */
class FieldBuilder implements FieldBuilderInterface
{
    public function addField($name, $field, $options, FormBuilderInterface $builder)
    {
        if (array_key_exists('data', $options) && $field->type == 'genemu_jquerydate') {
            $value = $options['data'];
            if (trim($value) != '') {
                $dateTimeValue = \DateTime::createFromFormat('d/m/Y', $value);
                if ($dateTimeValue != false) {
                    $options['data'] = $dateTimeValue;
                }
            } else {
                unset($options['data']);
            }
        }

        if (isset($field->multiple) && $field->multiple) {
            $this->addMultipleField($name, $field, $options, $builder);
        } else {
            $this->addSingleField($name, $field, $options, $builder);
        }
    }

    private function addSingleField($name, $field, $options, $builder)
    {
        $builder->add($name, $field->type, $options);
    }

    private function addMultipleField($name, $field, $options, $builder)
    {
        $builder->add($name, 'collection', array(
            'label' => $options['label'],
            'error_bubbling' => false,
            'type' =>  new VirtualType($options, $field),
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'required' => ($options['required']) ? $options['required'] : false,
            'attr' => array('class' => 'prototype'),
            'data' =>(array_key_exists('data', $options) && is_array($options['data'])) ? $options['data'] : null
        ));
    }

    /**
     * Length constraint on multiple field
     * @param unknown $formFields
     * @param unknown $builder
     */
    public function addMultipleFieldPostBindEvent($formFields, $builder)
    {
        $builder->addEventListener(FormEvents::POST_BIND, function (FormEvent $event) use ($builder, $formFields) {

            $form = $event->getForm();

            foreach ($formFields as $name => $field) {
                if (property_exists($field, 'multiple') && $field->multiple && (property_exists($field, 'constraints') && property_exists($field->constraints, 'Length')
                        && property_exists($field->constraints->Length, 'max'))) {
                    $fieldData = $form->get($name)->getData();

                    if (null != $fieldData && is_array($fieldData) && sizeof($fieldData) > 0) {
                        $maxLength = $field->constraints->Length->max;

                        $concat = "";

                        foreach ($fieldData as $data) {
                            if (is_array($data)) {
                                $concat .= $data['value'].";";
                            }
                        }

                        if (strlen($concat) > 0) {
                            $concat = substr($concat, 0, -1);
                        }

                        if (strlen($concat) > $maxLength) {
                            $form->get($name)->addError(new FormError("Le champ ne doit pas dépasser ".$maxLength." caractère".(($maxLength > 1) ? "s" : "")));
                        }
                    }
                }
            }

        });
    }

    /**
     * Modifies data before they are bound to the form
     * @param unknown $formFields
     * @param unknown $builder
     */
    public function alterDataPreSetDataEvent($formFields, $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $formFields) {

            $formData = $event->getData();

            if (null != $formData) {
                foreach ($formFields as $name => $field) {
                    //Altering date array to a \DateTime object if date type in fieldset or if date type
                    if ("fieldset" == $field->type) {
                        foreach ($field->options->subforms as $subFieldName => $subField) {
                            if ("date" == $subField->type && array_key_exists($name, $formData) && array_key_exists($subFieldName, $formData[$name])) {
                                $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s.u', $formData[$name][$subFieldName]['date']);
                                // try to get the date without milliseconds
                                if (!$dateTimeValue) {
                                    $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s', $formData[$name][$subFieldName]['date']);
                                }

                                if ($dateTimeValue != false) {
                                    $formData[$name][$subFieldName] = $dateTimeValue;
                                }
                            } else if ("fieldset" == $subField->type) {
                                //In case a fieldset contains another fieldset with date - better rewrite with recursive walker
                                foreach ($subField->attr->subforms as $subSubFieldName => $subSubField) {
                                    if ("date" == $subSubField->type && array_key_exists($name, $formData) && array_key_exists($subFieldName, $formData[$name]) && array_key_exists($subSubFieldName, $formData[$name][$subFieldName])) {
                                        $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s.u', $formData[$name][$subFieldName][$subSubFieldName]['date']);
                                        // try to get the date without milliseconds
                                        if (!$dateTimeValue) {
                                            $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s', $formData[$name][$subFieldName][$subSubFieldName]['date']);
                                        }

                                        if ($dateTimeValue != false) {
                                            $formData[$name][$subFieldName][$subSubFieldName] = $dateTimeValue;
                                        }
                                    }
                                }
                            }
                        }
                    } elseif ("date" == $field->type && array_key_exists($name, $formData)) {
                        $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s.u', $formData[$name]['date']);
                        // try to get the date without milliseconds
                        if (!$dateTimeValue) {
                            $dateTimeValue = \DateTime::createFromFormat('Y-m-d H:i:s', $formData[$name]['date']);
                        }

                        if ($dateTimeValue != false) {
                            $formData[$name] = $dateTimeValue;
                        }
                    }
                }
            }

            $event->setData($formData);
        });
    }

    /**
     * Number of elements constraint on associated fields
     * @param unknown $formFields
     * @param unknown $builder
     */
    public function addAssociatedFielPostBindEvent($formFields, $builder)
    {
        $builder->addEventListener(FormEvents::POST_BIND, function (FormEvent $event) use ($builder, $formFields) {

            $form = $event->getForm();

            $fieldRelatedList = array();

            foreach ($formFields as $name => $field) {
                if (property_exists($field, 'options') && property_exists($field->options, 'attr') && property_exists($field->options->attr, 'data-related')) {
                    $fieldRelated = $field->options->attr->{'data-related'};
                    $fieldSource = $field->options->attr->{'data-source'};

                    if (!in_array($fieldSource, $fieldRelatedList)) {
                        $nbElementsSource = sizeof($form->get($name)->getData());

                        //Searching related data field
                        foreach ($formFields as $nameRelatedItem => $fieldRelatedItem) {
                            if (property_exists($fieldRelatedItem, 'options') && property_exists($fieldRelatedItem->options, 'attr')
                                && property_exists($fieldRelatedItem->options->attr, 'data-source')) {
                                if ($fieldRelatedItem->options->attr->{'data-source'} == $fieldRelated) {
                                    $nbElementsRelated = sizeof($form->get($nameRelatedItem)->getData());

                                    if ($nbElementsRelated != $nbElementsSource) {
                                        $form->get($name)->addError(new FormError('Veuillez saisir autant de champ que pour '.$fieldRelatedItem->options->label));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
