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


class Menu implements ElementInterface
{
    /** @var Controller */
    protected $controller;

    /** @var string */
    protected $viewsDirectory;

    /** @var string */
    protected $template;

    public function __construct(Controller $controller, $viewsDirectory, $template)
    {
        $this->controller     = $controller;
        $this->viewsDirectory = $viewsDirectory;
        $this->template       = $template;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->controller->getForm()->getEntity();
    }

    public function getPath()
    {
        return $this->viewsDirectory . DIRECTORY_SEPARATOR . 'Include' . DIRECTORY_SEPARATOR . 'menu.html.twig';
    }

    public function getTemplate()
    {
        return $this->template;
    }
}