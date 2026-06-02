<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection;

use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;

abstract class Provider extends AbstractServiceProvider
{
    abstract public function requires(): array;

    abstract public function providedServices(): array;

    abstract public function definitions(Container $container): void;

    public function provides(string $id): bool
    {
        return in_array($id, $this->providedServices(), true);
    }

    public function register(): void
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $providers = $container->get(ServiceProviderAggregateInterface::class);

        $this->definitions($this->getContainer());

        foreach ($providers->getIterator() as $provider) {
            if (count(array_intersect($provider->requires(), $this->providedServices())) > 0) {
                $provider->definitions($this->getContainer());
            }
        }
    }
}
