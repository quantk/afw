<?php
declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';
define('ROOT_DIR', __DIR__.'/../');

try {
    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

    $app      = new \Afw\Application();
    $response = $app->run($request);
} catch (\Throwable $e) {
    echo $e->getMessage();
}
