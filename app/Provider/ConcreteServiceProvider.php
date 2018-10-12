<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:24
 */

namespace App\Provider;


use Afw\Component\Container\Provider\ServiceProviderInterface;
use App\Service\ConcreteService;

class ConcreteServiceProvider implements ServiceProviderInterface
{
//region SECTION: Getters/Setters
    public function getDefinitions(): array
    {
        return [
            \App\Service\ServiceInterface::class => function () {
                return new ConcreteService();
            },
        ];
    }
//endregion Getters/Setters
}