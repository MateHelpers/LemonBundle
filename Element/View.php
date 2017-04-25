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


class View implements ElementInterface
{
    /** @var ControllerMethod */
    protected $method;

    /** @var string ex: index */
    protected $name;

    /** @var string */
    protected $viewsDirectory;

    /** @var string */
    protected $template;

    /**
     * View constructor.
     * @param ControllerMethod $method
     * @param $viewDirectory
     * @param $name
     * @param $template
     */
    public function __construct(ControllerMethod $method, $name, $viewDirectory, $template)
    {
        $this->method = $method;
        $this->name = $name;
        $this->viewsDirectory = $viewDirectory;
        $this->template = $template;

        return $this;
    }

    public function getController()
    {
        return $this->method->getController();
    }

    public function getForm()
    {
        return $this->getController()->getForm();
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->getForm()->getEntity();
    }

    /**
     * @return ControllerMethod
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getBundle()
    {
        return $this->getController()->getBundle();
    }

    public function getShortPath()
    {
        $pathArray = explode('/', $this->viewsDirectory);

        $bundleName       = $this->getBundle()->getName();
        $parentFolderName = $pathArray[count($pathArray) - 1];
        $folderName       = $this->getEntity()->getName();
        $fileName         = $this->name;

        if ($bundleName == 'AppBundle')
            $bundleName = null;

        if ($this->method->hasForm())
            $fileName = 'form';

        return $bundleName . ':' . $parentFolderName . '/' . $folderName . ':' . $fileName . '.html.twig';
    }

    public function getPath()
    {
        $fileName = $this->name;

        if ($this->method->hasForm())
            $fileName = 'form';

        return
            $this->viewsDirectory.
            DIRECTORY_SEPARATOR.
            $this->getEntity()->getName().
            DIRECTORY_SEPARATOR.
            $fileName.
            '.html.twig';
    }
}