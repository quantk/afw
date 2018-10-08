<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:09
 */

namespace Afw;


use Afw\Component\Controller\Resolver\ControllerResolver;
use Afw\Component\Controller\Resolver\RouteParameters;
use Afw\Component\Kernel\KernelFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Application
{

    /**
     * Application constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \ReflectionException
     */
    public function run(Request $request): Response
    {
        /** @var RouteCollection $routeCollection */
        $routeCollection = require_once ROOT_DIR.'/app/routes.php';
        if (!$routeCollection instanceof RouteCollection) {
            throw new \RuntimeException('File routes must return RouteCollection');
        }

        $requestContext     = new RequestContext('/');
        $urlMatcher         = new UrlMatcher($routeCollection, $requestContext);
        $params             = $urlMatcher->match($request->getPathInfo());
        $routeParameters    = new RouteParameters($params);
        $controllerResolver = new ControllerResolver();

        $controllerObject = $controllerResolver->get($request, $routeParameters);
        $controller       = $controllerObject->getController();
        $rMethod          = new \ReflectionMethod($controller, $controllerObject->getAction());
        $actionParameters = $rMethod->getParameters();

        $params = [];
        foreach ($actionParameters as $actionParameter) {
            $parameterName = $actionParameter->getName();

            $paramInRequest = $routeParameters->get($parameterName);
            if ($paramInRequest) {
                $params[] = $paramInRequest;
            }
        }

        /** @var Response $response */
        $response = $rMethod->invokeArgs($controller, $params);

        return $response;
    }
}