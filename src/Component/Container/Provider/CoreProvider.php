<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:42
 */

namespace Afw\Component\Container\Provider;


use Afw\Application;
use Afw\Component\Templater\RendererInterface;
use Afw\Component\Templater\TwigRenderer;
use Afw\Component\Util\Env;
use DI\Container;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Twig_Environment;
use Twig_Loader_Filesystem;

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
            'routes'                                                              => function (Container $container) {
                /** @var RouteCollection $routeCollection */
                $routeCollection = require $container->get('routes.path');
                if (!$routeCollection instanceof RouteCollection) {
                    throw new \RuntimeException('File routes must return RouteCollection');
                }

                return $routeCollection;
            },
            Twig_Environment::class                                               => function (Container $container) {
                $loader = new Twig_Loader_Filesystem($container->get('template.path'));
                $twig   = new Twig_Environment(
                    $loader, array(
                    'cache' => Env::get('APP_ENV') === Application::PRODUCTION_MODE ? $container->get('cache.renderer.path') : false,
                )
                );

                return $twig;
            },
            RendererInterface::class                                              => function (Container $container) {
                return $container->get(TwigRenderer::class);
            },
        ];
    }
//endregion Getters/Setters
}