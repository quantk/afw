<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 12:03
 */

namespace Afw\Component\Reflection;


class Reflector
{
    /**
     * @param string $className
     * @param array $args
     * @return object
     * @throws \ReflectionException
     */
    public function initialize(string $className, array $args = [])
    {
        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf('Class %s doesn\'t exists', $className));
        }

        $rClass = new \ReflectionClass($className);

        return $rClass->newInstanceArgs($args);
    }
}