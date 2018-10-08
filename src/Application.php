<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:09
 */

namespace Afw;


use Afw\Component\Controller\Resolver\ControllerResolverInterface;
use Afw\Component\Controller\Resolver\RouteParameters;
use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Application
{
//region SECTION: Fields
    /**
     * @var ContainerInterface|Container
     */
    private $container;
    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;
    /**
     * @var ControllerResolverInterface
     */
    private $controllerResolver;
//endregion Fields

//region SECTION: Constructor
    /**
     * Application constructor.
     *
     * @param UrlMatcherInterface         $urlMatcher
     * @param ControllerResolverInterface $controllerResolver
     * @param ContainerInterface          $container
     */
    public function __construct(
        UrlMatcherInterface $urlMatcher,
        ControllerResolverInterface $controllerResolver,
        ContainerInterface $container = null
    ) {
        $this->container          = $container ?? new Container();
        $this->urlMatcher         = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param Request $request
     *
     * @return Response
     * @throws \ReflectionException
     */
    public function run(Request $request): Response
    {
        $urlMatcher = $this->urlMatcher;
        $params     = $urlMatcher->match($request->getPathInfo());

        $routeParameters    = new RouteParameters($params);
        $controllerResolver = $this->controllerResolver;

        $controllerObject = $controllerResolver->get($request, $routeParameters);
        $controller       = $controllerObject->getController();
        $rMethod          = new \ReflectionMethod($controller, $controllerObject->getAction());

        /** @var Response $response */
        $response = $this->container->call($rMethod->getClosure($controller), $params);

        return $response;
    }
//endregion Public
}