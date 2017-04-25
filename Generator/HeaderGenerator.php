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

namespace Mate\LemonBundle\Generator;


use Mate\LemonBundle\Element\Header;

class HeaderGenerator extends GeneratorAbstraction
{
    public function __construct(Header $header)
    {
        $this->element = $header;
    }

    public function buildPattern()
    {
        $this->pattern = [null];
    }
}