<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 18:35
 */

namespace Afw\Component\Templater;


final class TwigRenderer implements RendererInterface
{
    private $renderer;

    /**
     * Renderer constructor.
     */
    public function __construct(
        \Twig_Environment $twigEnvironment
    )
    {
        $this->renderer = $twigEnvironment;
    }

    /**
     * @param string $name | Template name
     * @param array $context | Template vars
     *
     * @return mixed
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $name, array $context = [])
    {
        return $this->renderer->render($name, $context);
    }
}