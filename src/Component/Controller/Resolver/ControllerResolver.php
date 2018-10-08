<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:31
 */

namespace Afw\Component\Controller\Resolver;


use Afw\Component\Controller\ControllerInterface;
use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver implements ControllerResolverInterface
{
//region SECTION: Getters/Setters
    /**
     * @var ContainerInterface|Container
     */
    private $container;

    /**
     * ControllerResolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }


    /**
     * @param Request         $request
     *
     * @param RouteParameters $routeParameters
     *
     * @return ControllerObject
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get(Request $request, RouteParameters $routeParameters)
    {
        $controller = $this->container->get($routeParameters->getController());

        return new ControllerObject($controller, $routeParameters->getControllerMethod());
    }
//endregion Getters/Setters
}