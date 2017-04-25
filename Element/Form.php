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
use Mate\LemonBundle\Helper\FormElementHelper;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Form implements ElementInterface
{
    /** @var Entity */
    protected $entity;

    /** @var BundleInterface */
    protected $bundle;

    /** @var ArrayCollection */
    protected $properties = array();

    /** @var string */
    protected $template;

    public function __construct(Entity $entity, BundleInterface $bundle, $template)
    {
        $this->entity     = $entity;
        $this->bundle     = $bundle;
        $this->template   = $template;
        $this->properties = new ArrayCollection();
    }

    public function getClassName()
    {
        return FormElementHelper::generateName($this->entity);
    }

    public function getClass()
    {
        return FormElementHelper::generateClass($this->bundle, $this->getClassName());
    }

    public function getPath()
    {
        return FormElementHelper::generatePath($this->getClassName(), $this->bundle);
    }

    public function getPrefix()
    {
        return FormElementHelper::generatePrefix($this->getClassName(), $this->bundle);
    }

    public function getNamespace()
    {
        return FormElementHelper::generateNamespace($this->bundle);
    }

    public function addProperty(FormProperty $property)
    {
        $this->properties->add($property);
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}