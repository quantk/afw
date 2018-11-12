<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.11.2018
 * Time: 13:31
 */

namespace Afw\Component\Console;


use DI\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * @var ContainerInterface
     * @Inject
     */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}