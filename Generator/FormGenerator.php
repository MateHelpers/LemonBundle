<?php
/**
 * This file is part of the MateLemonBundle package.
 *
 * (c) Mohamed Radhi Guennichi <https://www.mate.tn> <https://github.com/MateHelpers/LemonBundle>
 *
 * Email: rg@mate.tn - contact@mate.tn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mate\LemonBundle\Generator;

use Mate\LemonBundle\Element\Form;
use Mate\LemonBundle\Element\FormProperty;

class FormGenerator extends GeneratorAbstraction
{
    public function __construct(Form $form)
    {
        // we need to pass the form object to this class to use it later
        // on buildPattern method and run the standard logic from the
        // GeneratorAbstraction class
        $this->element = $form;
    }

    protected function buildPattern()
    {
        $formNamespace  = $this->element->getNamespace();
        $formClassName  = $this->element->getClassName();
        $formDataClass  = $this->element->getEntity()->getClass();
        $formPrefix     = $this->element->getPrefix();
        $formProperty = "\t\t\t->add('%s', %s)".PHP_EOL;
        $formProperties = null;

        $propertiesObjects = $this->element->getProperties();

        //Here we will concat form properties on the form template
        /** @var FormProperty $property */
        foreach ($propertiesObjects as $index => $property) {
            //remove new line for the last call
            if ($index == count($propertiesObjects) - 1)
                $formProperty = str_replace(PHP_EOL, '', $formProperty);

            $formProperties.= sprintf($formProperty, $property->getName(), $property->getClass());
        }

        $this->pattern = [
            'formNamespace'  => $formNamespace,
            'formClassName'  => $formClassName,
            'formDataClass'  => $formDataClass,
            'formPrefix'     => $formPrefix,
            'formProperties' => $formProperties
        ];
    }
}