<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:30
 */

namespace Afw\Component\Controller\Resolver;


use Psr\Http\Message\RequestInterface;

interface ControllerResolverInterface
{
//region SECTION: Getters/Setters
    /**
     * @param RequestInterface $request
     *
     * @param RouteParameters $routeParameters
     *
     * @return ControllerObject
     */
    public function get(RequestInterface $request, RouteParameters $routeParameters);
//endregion Getters/Setters
}