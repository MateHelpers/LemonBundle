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


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Mate\LemonBundle\Helper\Helper;
use Mate\LemonBundle\Helper\Inflect;

class Entity
{
    /** @var  ClassMetadata */
    protected $classMetadata;

    /** @var ArrayCollection */
    protected $properties = array();

    /**
     * Entity constructor.
     * @param ClassMetadata $classMetadata
     */
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
        $this->properties = new ArrayCollection();
    }

    public function getName()
    {
        return $this->classMetadata->reflClass->getShortName();
    }

    public function getLowerName()
    {
        return strtolower($this->getName());
    }

    public function getLowerPluralName()
    {
        return strtolower($this->getPluralName());
    }

    public function getPluralName()
    {
        return Inflect::pluralize($this->getName());
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }

    /**
     * @return array
     */
    public function getFieldMappings()
    {
        return $this->classMetadata->fieldMappings;
    }

    public function addProperty(EntityProperty $entityProperty)
    {
        $this->properties->add($entityProperty);
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getDesiredProperties()
    {
        $desiredProperties = array();

        /** @var EntityProperty $property */
        foreach ($this->properties as $property) {
            if ($property->isId()) continue;

            if ($property->isDesired())
                $desiredProperties[] = $property;
        }

        return $desiredProperties;
    }

    public function getClass()
    {
        return $this->classMetadata->getName();
    }
}