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


use Mate\LemonBundle\Element\Controller;
use Mate\LemonBundle\Element\ControllerMethod;

class ControllerGenerator extends GeneratorAbstraction
{
    public function __construct(Controller $controller)
    {
        $this->element = $controller;
    }

    public function buildPattern()
    {

        $form = $this->element->getForm();
        $entity = $form->getEntity();

        $methods = [];

        /** @var ControllerMethod $method */
        foreach ($this->element->getMethods() as $method) {
            $name          = $method->getLowerName();
            $viewShortPath = null;

            if ($method->hasView())
                $viewShortPath = $method->getView()->getShortPath();

            $currentMethod = [
                $name . 'RoutePath' => $method->getRoutePath(),
                $name . 'RouteName' => $method->getRouteName(),
                $name . 'Action'    => $method->getAction(),
                $name . 'View'      => $viewShortPath

            ];
            $methods = array_merge($methods, $currentMethod);
        }

        $globalData = [
            'controllerNameSpace'   => $this->element->getNamespace(),
            'controllerClassName'   => $this->element->getClassName(),
            'controllerGlobalRoute' => $this->element->getGlobalRoute(),
            'entityName'            => $entity->getName(),
            'entityClass'           => $entity->getClass(),
            'formClass'             => $form->getClass(),
            'formClassName'         => $form->getClassName(),
            'entityLowerName'       => $entity->getLowerName(),
            'entityPluralName'      => $entity->getPluralName(),
            'entityLowerPluralName' => $entity->getLowerPluralName(),
        ];

        $this->pattern = array_merge($methods, $globalData);


    }
}