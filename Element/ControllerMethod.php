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


use Mate\LemonBundle\Helper\ControllerElementHelper;

class ControllerMethod
{
    /** @var Controller */
    protected $controller;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $routeContainsId = false;

    /** @var bool */
    protected $hasForm = false;

    protected $hasView = true;

    protected $view = null;

    public function __construct(Controller $controller, $name, $routeContainsId = false, $hasForm = false, $hasView = true)
    {
        $this->controller       = $controller;
        $this->name             = $name;
        $this->routeContainsId  = $routeContainsId;
        $this->hasForm          = $hasForm;
        $this->hasView          = $hasView;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLowerName()
    {
        return strtolower($this->name);
    }

    public function getEntityPluralName()
    {
        return strtolower($this->controller->getForm()->getEntity()->getName() . 's');
    }

    public function getAction()
    {
        return ControllerElementHelper::generateMethodAction($this->getLowerName());
    }

    public function getRouteName()
    {
        return ControllerElementHelper::generateMethodRouteName($this->getEntityPluralName(), $this->getLowerName());
    }

    public function getRoutePath()
    {
        return ControllerElementHelper::generateMethodRoutePath($this->routeContainsId, $this->getLowerName());
    }

    public function hasForm()
    {
        return $this->hasForm;
    }

    public function hasView()
    {
        return $this->hasView;
    }

    /**
     * @return View|null
     */
    public function getView()
    {
        return $this->view;
    }

    public function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }
}