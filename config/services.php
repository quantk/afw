<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 22:59
 */

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

return [
    \Symfony\Component\Routing\Matcher\UrlMatcherInterface::class         => function(\DI\Container $container) {
        $requestContext     = new RequestContext('/');
        $urlMatcher         = new UrlMatcher($container->get('routes'), $requestContext);

        return $urlMatcher;
    },
    \Afw\Component\Controller\Resolver\ControllerResolverInterface::class => function(\DI\Container $container) {
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
];