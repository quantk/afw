<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 21:00
 */

namespace Tests\Afw\Component\Reflection;


use Afw\Component\Reflection\Reflector;
use PHPUnit\Framework\TestCase;

final class ReflectorTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testInitialize()
    {
        $reflector = new Reflector();

        /** @var TestStub $result */
        $result = $reflector->initialize(TestStub::class, ['val1', 'val2']);

        static::assertSame('val1', $result->getVal1());
        static::assertSame('val2', $result->getVal2());
    }

    /**
     * @throws \ReflectionException
     */
    public function testClassDoesntExist()
    {
        $reflector = new Reflector();

        static::expectException(\RuntimeException::class);
        /** @var TestStub $result */
        $result = $reflector->initialize('Class\\That\\Not\\Exist', ['val1', 'val2']);
    }
}