<?php
declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';
define('ROOT_DIR', __DIR__.'/../');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

try {
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

    $app      = new \Afw\Application();

    $response = $app->run($request);
    $response->send();
} catch (\Throwable $e) {
    throw $e;
}
