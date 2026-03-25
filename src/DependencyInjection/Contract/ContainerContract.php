<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection\Contract;

use Psr\Container\ContainerInterface;

interface ContainerContract extends ContainerInterface
{
    /**
     * Registers a service in the container.
     *
     * @param class-string $id The service identifier.
     */
    public function register(string $id): void;
    
    /**
     * Registers an alias for a service in the container.
     *
     * Allows a service to be aliased to another identifier, while still supporting autowiring for the target service.
     *
     * @param class-string $alias The alias name.
     * @param class-string $target The target service identifier that the alias points to.
     */
    public function alias(string $alias, string $target): void;

    /**
     * Registers a singleton service in the container.
     *
     * @param class-string $id The service identifier.
     * @param callable|string|null $result The resolver callable or alternate class name to instantiate.
     */
    public function singleton(string $id): void;

    /**
     * Registers a factory service in the container.
     *
     * @param class-string $id The service identifier.
     * @param callable|string|null $result The factory callable or alternate class name to instantiate.
     */
    public function factory(string $id, callable|string|null $result = null): void;

}
