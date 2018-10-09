<?php
declare(strict_types = 1);

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__.'/../vendor/autoload.php';
define('ROOT_DIR', __DIR__.'/../');
define('CONFIG_DIR', ROOT_DIR.'/config');

$dotenv = new Dotenv\Dotenv(ROOT_DIR);
$dotenv->load();

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $containerBuilder = new \DI\ContainerBuilder();
    $containerBuilder->addDefinitions(CONFIG_DIR.'/database.php');
    $containerBuilder->addDefinitions(
        [
            \Symfony\Component\Routing\Matcher\UrlMatcherInterface::class         => function (\DI\Container $container) {
                $requestContext = new RequestContext('/');
                $urlMatcher     = new UrlMatcher($container->get('routes'), $requestContext);

                return $urlMatcher;
            },
            \Afw\Component\Controller\Resolver\ControllerResolverInterface::class => function (\DI\Container $container) {
                return $container->get(\Afw\Component\Controller\Resolver\ControllerResolver::class);
            },
            \Doctrine\DBAL\Connection::class                                      => function (\DI\Container $container) {
                $connections = $container->get('connections');
                $connection  = $container->get('connection');

                $currentConnection = $connections[$connection] ?? null;
                if (null === $currentConnection) {
                    throw new \RuntimeException('Database connection not found');
                }

                $config           = new \Doctrine\DBAL\Configuration();
                $connectionParams = array(
                    'url'         => $currentConnection['url'],
                    'search_path' => 'afw.public',
                );

                $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

                return $conn;
            },
        ]
    );
    $containerBuilder->addDefinitions(CONFIG_DIR.'/services.php');
    $containerBuilder->useAnnotations(true);

    $container = $containerBuilder->build();

    $container->set(\Symfony\Component\HttpFoundation\Request::class, $request);

    /** @var RouteCollection $routeCollection */
    $routeCollection = require_once ROOT_DIR.'/app/routes.php';
    if (!$routeCollection instanceof RouteCollection) {
        throw new \RuntimeException('File routes must return RouteCollection');
    }
    $container->set('routes', $routeCollection);

    /** @var \Afw\Application $app */
    $app      = $container->get(\Afw\Application::class);

    $response = $app->run($request);
    $response->send();
} catch (\Throwable $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw $e;
}
