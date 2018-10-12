<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 18:32
 */

namespace Afw\Component\Templater;


interface RendererInterface
{
    /**
     * @param string $name | Template name
     * @param array $context | Template vars
     *
     * @return mixed
     */
    public function render(string $name, array $context = []);
}