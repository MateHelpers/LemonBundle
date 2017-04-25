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


use Mate\LemonBundle\Element\ElementInterface;
use Mate\LemonBundle\Element\EntityProperty;
use Mate\LemonBundle\Element\View;

class ViewGenerator extends GeneratorAbstraction
{
    /** @var ElementInterface */
    protected $element;

    public function __construct(View $view)
    {
        $this->element = $view;
    }

    public function buildPattern()
    {
        $entityLowerName = $this->element->getEntity()->getLowerName();
        $entityLowerPluralName = $this->element->getEntity()->getLowerPluralName();

        $entityThTag = '';
        $entityTdTag = '';

        $allowedTypes = [
            'string',
            'text',
            'date',
            'datetime',
            'boolean'
        ];

        /** @var EntityProperty $property */
        foreach ($this->element->getEntity()->getDesiredProperties() as $property) {
            if (in_array($propertyType = $property->getType(), $allowedTypes)) {
                $propertyName = $property->getName();

                $entityThTag.= str_repeat("\t", 5) . "<th>$propertyName</th>" . PHP_EOL;

                $dateFormat = '';
                if ($propertyType == 'date' || $propertyType == 'datetime')
                    $dateFormat = '|date(\'Y/m/d\')';

                $entityTdTag.= str_repeat("\t", 6) . "<td>{{ $entityLowerName.$propertyName"."$dateFormat"." }}</td>" . PHP_EOL;
            }
        }

        $this->pattern = [
            'entityLowerName' => $entityLowerName,
            'entityLowerPluralName' => $entityLowerPluralName,
            'entityThTag' => $entityThTag,
            'entityTdTag' => $entityTdTag,
            'updateRoutePath' => $this->element->getController()->getMethod('update')->getRouteName(),
            'deleteRoutePath' => $this->element->getController()->getMethod('delete')->getRouteName(),
        ];


    }
}