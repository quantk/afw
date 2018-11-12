<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.11.2018
 * Time: 13:40
 */

namespace Afw;


use Psr\Container\ContainerInterface;

class Kernel
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Kernel constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getRootDir()
    {
        return $this->container->get('root.path');
    }

    public function getConfigPath()
    {
        return $this->container->get('config.path');
    }
}