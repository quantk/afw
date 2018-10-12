<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:42
 */

namespace Afw\Component\Container\Provider;


use DI\Container;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

final class CoreProvider implements ServiceProviderInterface
{
//region SECTION: Getters/Setters
    public function getDefinitions(): array
    {
        return [
            \Symfony\Component\Routing\Matcher\UrlMatcherInterface::class         => function (Container $container) {
                $requestContext = new RequestContext('/');

                return new UrlMatcher($container->get('routes'), $requestContext);
            },
            \Afw\Component\Controller\Resolver\ControllerResolverInterface::class => function (Container $container) {
                return $container->get(\Afw\Component\Controller\Resolver\ControllerResolver::class);
            },
            'routes'                                                              => function () {
                /** @var RouteCollection $routeCollection */
                $routeCollection = require ROOT_DIR.'/app/routes.php';
                if (!$routeCollection instanceof RouteCollection) {
                    throw new \RuntimeException('File routes must return RouteCollection');
                }

                return $routeCollection;
            },
        ];
    }
//endregion Getters/Setters
}