<?php
declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

try {
    $app = new \Afw\Application($request);
    $app->run();
} catch (\Throwable $e) {

}
