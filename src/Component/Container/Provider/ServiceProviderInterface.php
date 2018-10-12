<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:24
 */

namespace Afw\Component\Container\Provider;


interface ServiceProviderInterface
{
    public function getDefinitions(): array;
}