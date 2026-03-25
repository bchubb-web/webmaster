<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection;

/**
 * 
 */
class Container implements Contract\ContainerContract
{
    protected array $services = [];

    protected array $resolving = [];

    public function register(string $id): void
    {
        $this->validateId($id);
    }

    public function singleton(string $id): void
    {
        $this->validateId($id);
    }

    public function alias(string $id, string $service): void
    {
        $this->validateId($id);
        $this->validateService($service);
    }

    public function factory(string $id, callable $factory): void
    {
        $this->validateId($id);
    }

    public function get(string $id): mixed
    {
        $this->validateId($id);
    }

    public function has(string $id): bool
    {
        $this->validateId($id);
    }

    /**
     * Validates that the given ID is a valid interface or class name.
     *
     * @param string $id The ID to validate.
     *
     * @throws \InvalidArgumentException If the ID is not a valid interface or class name.
     */
    protected function validateId(string $id, bool $ignoreRegistered = true): void
    {
        if (
            !interface_exists($id) && !class_exists($id)
        ) {
            throw new \InvalidArgumentException("The ID '$id' is not a valid interface or class name.");
        }

        if (!$ignoreRegistered && !array_key_exists($id, $this->services)) {
            throw new \InvalidArgumentException("The ID '$id' is not registered in the container.");
        }
    }

    protected function validateService(string $service): void
    {
        if (!class_exists($service) && !array_key_exists($service, $this->services)) {
            throw new \InvalidArgumentException("The service '$service' is not registered in the container.");
        }
    }
}
