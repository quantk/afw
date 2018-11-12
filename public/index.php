<?php
declare(strict_types = 1);

require_once '../vendor/autoload.php';

try {
    /** @var \DI\Container $container */
    $rootDir = './';
    $container = require_once '../src/bootstrap.php';
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
