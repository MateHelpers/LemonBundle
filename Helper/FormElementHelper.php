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

use Symfony\Component\Form\Extension\Core\Type as FormLemonType;
use Mate\LemonBundle\Element\Entity;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class FormElementHelper
{
    const PROPERTY_NS_PREFIX = 'FormLemonType\\';
    const FORM_NS_POSTFIX = '\\Form';

    public static function generateName(Entity $entity)
    {
        return $entity->getName() . 'Type';
    }

    public static function generateClass(BundleInterface $bundle, $className)
    {
        return $bundle->getNamespace() . self::FORM_NS_POSTFIX . '\\' . $className;
    }

    public static function generateNamespace(BundleInterface $bundle)
    {
        return $bundle->getNamespace() . self::FORM_NS_POSTFIX;
    }

    public static function generatePrefix($className, BundleInterface $bundle)
    {
        $bundleName = $bundle->getName();

        $prefixCamelCase = $bundleName.$className.'Form';

        return Helper::from_camel_to_snake($prefixCamelCase);
    }

    public static function generatePath($className, BundleInterface $bundle)
    {
        $bundlePath = $bundle->getPath();

        return $bundlePath.DIRECTORY_SEPARATOR.'Form'.DIRECTORY_SEPARATOR.$className.'.php';
    }

    public static function generatePropertyName($name)
    {
        return Helper::to_camel_case($name);
    }

    public static function generatePropertyClass($type)
    {
        switch ($type) {
            case 'string':
                return self::PROPERTY_NS_PREFIX. 'TextType::class';
            case 'text':
                return self::PROPERTY_NS_PREFIX. 'TextareaType::class';
            case 'datetime':
                return self::PROPERTY_NS_PREFIX. 'DateTimeType::class';
            case 'date':
                return self::PROPERTY_NS_PREFIX. 'DateType::class';
            case 'integer':
                return self::PROPERTY_NS_PREFIX. 'NumberType::class';
            default: return null;
        }
    }
}