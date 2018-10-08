<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:30
 */

namespace Afw\Component\Controller\Resolver;


use Symfony\Component\HttpFoundation\Request;

interface ControllerResolverInterface
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
    public function get(Request $request, RouteParameters $routeParameters);
//endregion Getters/Setters
}