<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 22:45
 */

namespace Afw\Component\Container\Provider;


use DI\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerProvider implements ServiceProviderInterface
{

    public function getDefinitions(): array
    {
        return [
            LoggerInterface::class => function (Container $container) {
                $rootDir = $container->get('root.path');
                $env = $container->get('env');
                // create a log channel
                $log = new Logger('logger');
                $log->pushHandler(new StreamHandler(sprintf('%s/var/logs/%s.log', $rootDir, $env), Logger::WARNING));

                return $log;
            }
        ];
    }
}