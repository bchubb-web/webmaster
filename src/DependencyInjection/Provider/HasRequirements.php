<?php

namespace Webmaster\DependencyInjection\Provider;

use League\Container\Container;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;

trait HasRequirements
{
    abstract public function getContainer(): Container;

    abstract public function requires(): array;

    abstract public function definitions(Container $container): void;

    public function register(): void
    {
        /** @var Container $container */
        $container = $this->getContainer();
        $providers = $container->get(ServiceProviderAggregateInterface::class);

        foreach ($this->requires() as $requirement) {
            foreach ($providers->getIterator() as $provider) {
                if ($provider->provides($requirement)) {
                    $provider->register($requirement);
                }
            }
        }

        $this->definitions($this->getContainer());
    }
}
