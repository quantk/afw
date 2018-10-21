<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 21:07
 */

namespace Tests\Afw\Filesystem;


use Afw\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

final class FilesystemTest extends TestCase
{
    public function testGetClassesFromDirectory()
    {
        $filesystem = new Filesystem();

        $classes = $filesystem->getClassesFromDirectory(__DIR__ . '/Directory');
        static::assertSame($classes, [
            0 => 'ClassFirst',
            1 => 'ClassSecond',
            2 => 'ClassThird',
        ]);
    }
}