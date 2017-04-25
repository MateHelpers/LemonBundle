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


class EntityProperty
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var bool */
    protected $id = false;

    /** @var bool */
    protected $desired = false;

    /**
     * EntityProperty constructor.
     * @param array $fieldMapping
     * @param bool $isDesired
     */
    public function __construct(array $fieldMapping, $isDesired = false)
    {
        $this->name = $fieldMapping['fieldName'];
        $this->type = $fieldMapping['type'];

        if (array_key_exists('id', $fieldMapping)) {
            $this->id = boolval($fieldMapping['id']);
        }

        $this->desired = $isDesired;
    }

    public function setAsDesired()
    {
        if (!$this->desired)
            $this->desired = true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isDesired()
    {
        return $this->desired;
    }
}