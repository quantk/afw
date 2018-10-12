<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 19:00
 */

namespace Afw\Component\Util;


final class Env
{
    /**
     * @param      $varName
     * @param null $default
     *
     * @return array|false|null|string
     */
    final public static function get($varName, $default = null)
    {
        return getenv($varName) ?? $default;
    }
}