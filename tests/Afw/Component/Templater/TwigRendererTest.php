<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 20:57
 */

namespace Tests\Afw\Component\Templater;


use Afw\Component\Templater\TwigRenderer;
use PHPUnit\Framework\TestCase;

final class TwigRendererTest extends TestCase
{
    public function testRender()
    {
        $twig = $this->createMock(\Twig_Environment::class);

        $renderer = new TwigRenderer($twig);

        $twig->expects(static::once())->method('render');
        $renderer->render('', []);
    }
}