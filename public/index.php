<?php
declare(strict_types = 1);

try {
    /** @var \DI\Container $container */
    $container = require_once './bootstrap.php';
    $logger = $container->get(\Psr\Log\LoggerInterface::class);

    /** @var \Afw\Application $app */
    $app = $container->get(\Afw\Application::class);

    $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals(
        $_SERVER,
        $_GET,
        $_POST,
        $_COOKIE,
        $_FILES
    );

//    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $response = $app->run($request);

    sendResponse($response);
} catch (\Throwable $e) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw $e;
}
