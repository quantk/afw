<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 20:54
 */

namespace Tests\Afw\Component\Util;


use Afw\Component\Util\Env;
use PHPUnit\Framework\TestCase;

final class EnvTest extends TestCase
{
    public function testGet()
    {
        $value = 'test';
        putenv('test=test');

        static::assertSame($value, Env::get('test'));
    }
}