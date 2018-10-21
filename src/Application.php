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
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Zend\Diactoros\Response\HtmlResponse;

class Application
{
//region SECTION: Fields
    public const DEVELOMPMENT_MODE = 'dev';
    public const PRODUCTION_MODE = 'prod';

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
        ContainerInterface $container
    ) {
        $this->container          = $container;
        $this->urlMatcher         = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param RequestInterface $request
     *
     * @return \Zend\Diactoros\Response
     * @throws \ReflectionException
     */
    public function run(RequestInterface $request): \Zend\Diactoros\Response
    {
        $this->container->set(RequestInterface::class, $request);

        $urlMatcher = $this->urlMatcher;

        try {
            $params = $urlMatcher->match($request->getUri()->getPath());
        } catch (ResourceNotFoundException $notFoundException) {
            return new \Zend\Diactoros\Response('Page not found', 404);
        }

        $routeParameters    = new RouteParameters($params);
        $controllerResolver = $this->controllerResolver;

        $controllerObject = $controllerResolver->get($request, $routeParameters);
        $controller       = $controllerObject->getController();
        $rMethod          = new \ReflectionMethod($controller, $controllerObject->getAction());

        $controllerResult = $this->container->call($rMethod->getClosure($controller), $params);

        return $controllerResult instanceof ResponseInterface ? $controllerResult : new HtmlResponse($controllerResult);
    }
//endregion Public
}