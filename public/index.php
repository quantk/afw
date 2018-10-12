<?php
declare(strict_types = 1);

use App\Service\ServiceInterface;

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
    $containerBootstrap = new \Afw\Component\Container\ContainerBootstrap(new \DI\ContainerBuilder());
    $providers = require CONFIG_DIR.DIRECTORY_SEPARATOR.'providers.php';

    $coreProviders = [
        \Afw\Component\Container\Provider\CoreProvider::class,
        \Afw\Component\Container\Provider\DatabaseProvider::class,
        \Afw\Component\Container\Provider\ConfigProvider::class
    ];

    $container = $containerBootstrap->buildContainer(...$coreProviders, ...$providers);

    $service = $container->get(ServiceInterface::class);

    /** @var \Afw\Application $app */
    $app = $container->get(\Afw\Application::class);

    $response = $app->run($request);
    $response->send();
} catch (\Throwable $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw $e;
}
