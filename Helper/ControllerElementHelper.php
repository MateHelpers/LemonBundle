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

namespace Mate\LemonBundle\Helper;

use Mate\LemonBundle\Element\Entity;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class ControllerElementHelper
{
    const CONTROLLER_NS_POSTFIX = '\\Controller';
    const METHOD_ROUTE_PREFIX = 'mate_lemon_';

    public static function generateName(Entity $entity)
    {
        //here the "s" added to make the name of the controller as plural
        return $entity->getName() . 'sController';
    }

    /**
     * WARNING: This method for the moment accept only one folder without sub folders
     * @param BundleInterface $bundle
     * @param null $folder
     * @return string
     */
    public static function generateNamespace(BundleInterface $bundle, $folder = null)
    {
        if ($folder)
            $folder = '\\' . $folder;

        return $bundle->getNamespace() . self::CONTROLLER_NS_POSTFIX . $folder;
    }

    /**
     * WARNING: This method for the moment accept only one folder without sub folders
     * @param $className
     * @param BundleInterface $bundle
     * @param null $folder
     * @return string
     */
    public static function generatePath($className, BundleInterface $bundle, $folder = null)
    {
        $bundlePath = $bundle->getPath();
        $folderPath = null;

        if ($folder) {
            $folderPath = $folder.DIRECTORY_SEPARATOR;
        }

        return
            $bundlePath.
            DIRECTORY_SEPARATOR.
            'Controller'.
            DIRECTORY_SEPARATOR.
            $folderPath.
            $className.
            '.php';
    }

    public static function generateGlobalRoute(Entity $entity)
    {
        return DIRECTORY_SEPARATOR . strtolower($entity->getName()) . 's';
    }

    public static function generateMethodAction($lowerName)
    {
        return $lowerName . 'Action';
    }

    public static function generateMethodRouteName($entityPluralName, $lowerName)
    {
        return self::METHOD_ROUTE_PREFIX . $entityPluralName . '_' . $lowerName;
    }

    public static function generateMethodRoutePath($routeContainsId, $lowerName)
    {
        $id = DIRECTORY_SEPARATOR . '{id}' . DIRECTORY_SEPARATOR;

        if ($routeContainsId)
            return $id . $lowerName;

        if ($lowerName == 'index')
            return DIRECTORY_SEPARATOR;

        return DIRECTORY_SEPARATOR . $lowerName;
    }
}