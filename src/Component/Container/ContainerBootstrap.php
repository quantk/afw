<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:20
 */

namespace Afw\Component\Container;


use Afw\Component\Container\Provider\ServiceProviderInterface;
use DI\Container;
use DI\ContainerBuilder;

final class ContainerBootstrap
{
    /**
     * @var ContainerBuilder
     */
    private $builder;
//region SECTION: Constructor

    /**
     * ContainerBootstrap constructor.
     *
     * @param ContainerBuilder $builder
     */
    public function __construct(ContainerBuilder $builder)
    {
        $this->builder = $builder;
    }
//endregion Constructor

//region SECTION: Private
    /**
     * @param $providers
     *
     * @return array
     */
    private function boot($providers): void
    {
        $definitions = [];
        foreach ($providers as $provider) {
            $isValidProvider = $this->validateProvider($provider);
            if (false === $isValidProvider) {
                throw new \RuntimeException(sprintf('ServiceProvider %s is not implement %s', $provider, ServiceProviderInterface::class));
            }

            /** @var ServiceProviderInterface $providerInstance */
            $providerInstance = new $provider();
            $definitions[] = $providerInstance->getDefinitions();
        }

        $this->builder->addDefinitions(array_merge(...$definitions));
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    private function validateProvider($provider): bool
    {
        if (!is_subclass_of($provider, ServiceProviderInterface::class)) {
            return false;
        }

        return true;
    }
//endregion Private

//region SECTION: Getters/Setters
    /**
     * @param mixed ...$providers
     *
     * @return array
     * @throws \Exception
     */
    final public function buildContainer(...$providers): Container
    {
        $this->boot($providers);

        return $this->builder->build();
    }
//endregion Getters/Setters
}