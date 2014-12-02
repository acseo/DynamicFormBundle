<?php

namespace ACSEO\Bundle\DynamicFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldsetType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'      => false,
            'subforms'   => array(),
            'options'    => array()
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options['subforms'])) {
            foreach ($options['subforms'] as $f) {
                $builder->add($f['name'], $f['type'], $f['attr']);
            }
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['title']) || $options['title'] !== false) {
            $view->vars['title'] = $options['title'];
        }
    }

    public function getName()
    {
        return 'fieldset';
    }
}
