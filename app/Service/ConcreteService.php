<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:23
 */

namespace App\Service;


class ConcreteService implements ServiceInterface
{

    public function doSomeWork()
    {
        echo 'DOING'.PHP_EOL;
    }
}