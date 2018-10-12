<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:41
 */

namespace Afw\Component\Container\Provider;


final class DatabaseProvider implements ServiceProviderInterface
{

    public function getDefinitions(): array
    {
        return [
            \Doctrine\DBAL\Connection::class => function (\DI\Container $container) {
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

                return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            }
        ];
    }
}