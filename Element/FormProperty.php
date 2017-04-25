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

namespace Mate\LemonBundle\Element;


use Mate\LemonBundle\Helper\FormElementHelper;

class FormProperty
{
    /** @var EntityProperty */
    protected $property;

    /** @var string */
    protected $typeClass;

    /** @var array */
    protected $options = array();

    public function __construct(EntityProperty $property, $options = array())
    {
        $this->property = $property;
        $this->options = $options;
    }

    public function getClass()
    {
        return FormElementHelper::generatePropertyClass($this->property->getType());
    }

    public function getName()
    {
        return FormElementHelper::generatePropertyName($this->property->getName());
    }
}