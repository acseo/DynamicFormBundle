<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Type;

use ACSEO\Bundle\DynamicFormBundle\Form\Field\FieldBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Virtual Type : used for collection form type
 */
class VirtualType extends AbstractType
{
    private $options;
    private $field;

    /**
     * VirtualType constructor.
     * @param array $options
     * @param mixed $field
     */
    public function __construct(array $options, $field)
    {
        $this->options = $options;
        $this->field = $field;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (array_key_exists('data', $this->options) && is_array($this->options['data'])) {
            unset($this->options['data']);
        }

        $builder->add('value', FieldBuilder::getFormTypeClass($this->field->type), $this->options);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'virtual';
    }
}
