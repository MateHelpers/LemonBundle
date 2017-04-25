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


class MenuPartial extends Menu
{
    protected $parentTemplate;

    public function getPartialTemplate()
    {
        return $this->template;
    }

    /**
     * We override this function to set the original template
     * for the partial menu the generated menu
     * @return bool|string
     */
    public function getTemplate()
    {
        return file_get_contents($this->getPath());
    }
}