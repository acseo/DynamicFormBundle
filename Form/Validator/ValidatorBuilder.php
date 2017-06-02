<?php

/*
* This file is part of the ACSEO\Bundle\DynamicFormBundle Symfony bundle.
*
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace ACSEO\Bundle\DynamicFormBundle\Form\Validator;

use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class ValidatorBuilder
 */
class ValidatorBuilder implements ValidatorBuilderInterface
{
    /**
     * Generate validators for form field
     *
     * @param $field
     * @return array|bool
     */
    public function buildConstraints($field)
    {
        if (!$this->hasConstraints($field)) {
            return false;
        }

        //cast object to array recursively (not sure about perf)
        $rawConstraints = json_decode(json_encode($field->constraints), true);
        $constraints = array();

        foreach ($rawConstraints as $rule => $options) {

            $className = $this->guessValidatorClass($rule);

            $constraints[] = $this->instantiateValidator($className, $options);
        }


        return $constraints;
    }

    private function instantiateValidator($className, $options)
    {
        try {
            return new $className($options);
        } catch (ConstraintDefinitionException $e) {
            // to avoid error "No default option is configured for constraint"
            // i.e Constraints with no constructor options
            return new $className();
        }
    }

    private function guessValidatorClass($ruleName)
    {
        $className = 'Symfony\Component\Validator\Constraints\\'.$ruleName;

        if (class_exists($className)) {
            return $className;
        } elseif (class_exists($ruleName)) {
            return $ruleName;
        }

        throw new ValidatorException(sprintf('"%s" is not defined', $ruleName));
    }

    private function hasConstraints($field)
    {
        if (isset($field->constraints) && $field->constraints) {
            return true;
        }

        return false;
    }
}
