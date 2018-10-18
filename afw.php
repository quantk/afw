<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:34
 */

use Symfony\Component\Console\Application;

$container = require_once './public/bootstrap.php';

$application = new Application();
$application->add($container->make(\Afw\Component\Migration\Console\MigrateCommand::class));

/** @noinspection PhpUnhandledExceptionInspection */
$application->run();