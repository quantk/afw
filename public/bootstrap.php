<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 13:29
 */

require_once __DIR__ . '/../vendor/autoload.php';

function is_cli()
{
    if (defined('STDIN')) {
        return true;
    }

    if (php_sapi_name() === 'cli') {
        return true;
    }

    if (array_key_exists('SHELL', $_ENV)) {
        return true;
    }

    if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
        return true;
    }

    if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
        return true;
    }

    return false;
}

if (!is_cli()) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}


define('ROOT_DIR', __DIR__ . '/..');
define('CONFIG_DIR', ROOT_DIR . '/config');

$dotenv = new Dotenv\Dotenv(ROOT_DIR);
$dotenv->load();

if (\Afw\Component\Util\Env::get('APP_ENV') === \Afw\Application::PRODUCTION_MODE) {
    ini_set("display_errors", 0);
    ini_set("log_errors", 1);

    ini_set("error_log", "syslog");
}

$containerBootstrap = new \Afw\Component\Container\ContainerBootstrap(new \DI\ContainerBuilder());
$providers = require CONFIG_DIR . DIRECTORY_SEPARATOR . 'providers.php';

$coreProviders = [
    \Afw\Component\Container\Provider\CoreProvider::class,
    \Afw\Component\Container\Provider\DatabaseProvider::class,
    \Afw\Component\Container\Provider\ConfigProvider::class,
    \Afw\Component\Container\Provider\ParametersProvider::class
];

$container = $containerBootstrap->buildContainer(...$coreProviders, ...$providers);

return $container;