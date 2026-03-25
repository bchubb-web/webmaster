<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection\Registration;

use ReflectionClass;
use Webmaster\DependencyInjection\Contract\ContainerContract;
use Webmaster\DependencyInjection\Contract\ServiceRegistrationContract;

class Service implements ServiceRegistrationContract
{
    protected ReflectionClass $reflection;

    public function __construct(
        protected ContainerContract $container,
        protected string $id,
    ) {
        $this->reflection = new ReflectionClass($id);
    }

    public function resolve(): object
    {
        foreach ($this->reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
            if (!$parameter->hasType() || $parameter->getType()->isBuiltin()) {
                throw new \LogicException(sprintf(
                    'Cannot resolve service "%s" because parameter "%s" is not a class type.',
                    $this->id,
                    $parameter->getName()
                ));
            }
        }
    }
}
