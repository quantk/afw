<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 13:29
 */

use Afw\Application;
use Afw\Component\Container\ContainerBootstrap;

if (!isCli()) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}
$rootDir = getRootDir();

$dotenv = new Dotenv\Dotenv(getRootDir());
$dotenv->load();

if (Application::PRODUCTION_MODE === \Afw\Component\Util\Env::get('APP_ENV')) {
    ini_set("display_errors", '0');
    ini_set("log_errors", '1');

    ini_set("error_log", "syslog");
}

$containerBootstrap = new ContainerBootstrap(new \DI\ContainerBuilder(), $rootDir);
$providers = require_once implode(DIRECTORY_SEPARATOR, [$rootDir, 'config', 'providers.php']);

$coreProviders = [
    \Afw\Component\Container\Provider\LoggerProvider::class,
    \Afw\Component\Container\Provider\CoreProvider::class,
    \Afw\Component\Container\Provider\DatabaseProvider::class,
    \Afw\Component\Container\Provider\ConfigProvider::class,
    \Afw\Component\Container\Provider\ParametersProvider::class
];

$container = $containerBootstrap->buildContainer(...$coreProviders, ...$providers);

return $container;