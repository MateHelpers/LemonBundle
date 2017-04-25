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


class Layout implements ElementInterface
{
    /** @var string */
    protected $viewsDirectory;

    /** @var string */
    protected $template;

    /**
     * Layout constructor.
     * @param $viewsDirectory
     * @param $template
     */
    public function __construct($viewsDirectory, $template)
    {
        $this->viewsDirectory = $viewsDirectory;
        $this->template       = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->viewsDirectory . DIRECTORY_SEPARATOR . 'layout.html.twig';
    }
}