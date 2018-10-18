<?php
declare(strict_types = 1);

try {
    $container = require_once './bootstrap.php';

    /** @var \Afw\Application $app */
    $app = $container->get(\Afw\Application::class);

    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $response = $app->run($request);
    $response->send();
} catch (\Throwable $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw $e;
}
