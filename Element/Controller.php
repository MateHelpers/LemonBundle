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
use Mate\LemonBundle\Helper\ControllerElementHelper;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Controller implements ElementInterface
{
    /** @var BundleInterface */
    protected $bundle;

    /** @var Form */
    protected $form;

    /** @var string */
    protected $template;

    /** @var string|null */
    protected $folder = null;

    /** @var ArrayCollection */
    protected $methods = array();

    public function __construct(BundleInterface $bundle, Form $form, $template, $folder = null)
    {
        $this->bundle = $bundle;
        $this->form   = $form;
        $this->template = $template;
        $this->folder = $folder;

        $this->methods = new ArrayCollection();
    }

    public function addMethod($name, $routeContainsId, $hasForm, $hasView, $view = null)
    {
        $this->methods->add(new ControllerMethod($this, $name, $routeContainsId, $hasForm, $hasView, $view));
        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param $name
     * @return ControllerMethod|null
     */
    public function getMethod($name)
    {
        $name = strtolower($name);

        /** @var ControllerMethod $method */
        foreach ($this->getMethods() as $method) {
            if (strtolower($method->getName()) === $name) {
                return $method;
            }
        }

        return null;
    }

    public function getClassName()
    {
        return ControllerElementHelper::generateName($this->form->getEntity());
    }

    public function getNamespace()
    {
        return ControllerElementHelper::generateNamespace($this->bundle, $this->folder);
    }

    public function getPath()
    {
        return ControllerElementHelper::generatePath($this->getClassName(), $this->bundle, $this->folder);
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getGlobalRoute()
    {
        return ControllerElementHelper::generateGlobalRoute($this->form->getEntity());
    }

    /**
     * @return BundleInterface
     */
    public function getBundle()
    {
        return $this->bundle;
    }
}