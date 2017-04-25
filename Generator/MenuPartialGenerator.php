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


use Mate\LemonBundle\Element\MenuPartial;

class MenuPartialGenerator extends GeneratorAbstraction
{
    public function __construct(MenuPartial $menuPartial)
    {
        $this->element = $menuPartial;
    }

    public function buildPattern()
    {
        $this->pattern = [
            'restData'          => $this->element->getPartialTemplate(),
            'indexRouteName'    => $this->element->getController()->getMethod('index')->getRouteName(),
            'createRouteName'   => $this->element->getController()->getMethod('create')->getRouteName(),
            'entityName'        => $this->element->getEntity()->getName(),
            'entityPluralName'  => $this->element->getEntity()->getPluralName()
        ];
    }
}