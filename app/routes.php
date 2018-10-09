<?php
declare(strict_types=1);

$routeCollection = new \Symfony\Component\Routing\RouteCollection();

$routeCollection->add(
    'index',
    new \Symfony\Component\Routing\Route(
        '/', [
            '_controller' => \App\Controller\Controller::class,
            '_action'     => 'indexAction',
        ]
    )
);

return $routeCollection;