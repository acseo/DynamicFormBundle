<?php

namespace Eliophot\Bundle\DynamicFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FieldsetType
 * @package Eliophot\Bundle\DynamicFormBundle\Form\Type
 */
class FieldsetType extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'      => false,
            'subforms'   => array(),
            'options'    => array(),
        ));
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options['subforms'])) {
            foreach ($options['subforms'] as $f) {
                $builder->add($f['name'], $f['type'], $f['attr']);
            }
        }
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['title']) || $options['title'] !== false) {
            $view->vars['title'] = $options['title'];
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'fieldset';
    }
}
