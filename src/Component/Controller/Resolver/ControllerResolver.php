<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:31
 */

namespace Afw\Component\Controller\Resolver;


use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

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
     * @param RequestInterface $request
     *
     * @param RouteParameters $routeParameters
     *
     * @return ControllerObject
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function get(RequestInterface $request, RouteParameters $routeParameters)
    {
        $controller = $this->container->make($routeParameters->getController());

        return new ControllerObject($controller, $routeParameters->getControllerMethod());
    }
//endregion Getters/Setters
}