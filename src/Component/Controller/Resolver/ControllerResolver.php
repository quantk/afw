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
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver implements ControllerResolverInterface
{
//region SECTION: Getters/Setters
    /**
     * @param Request         $request
     *
     * @param RouteParameters $routeParameters
     *
     * @return ControllerObject
     * @throws \ReflectionException
     */
    public function get(Request $request, RouteParameters $routeParameters)
    {
        $rController = new \ReflectionClass($routeParameters->getController());
        /** @var ControllerInterface $controller */
        $controller = $rController->newInstanceWithoutConstructor();

        return new ControllerObject($controller, $routeParameters->getControllerMethod());
    }
//endregion Getters/Setters
}