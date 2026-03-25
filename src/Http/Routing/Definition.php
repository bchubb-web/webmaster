<?php

namespace Webmaster\Http\Routing;

use Symfony\Component\Routing\Route;
use function Opis\Closure\serialize as serializeClosure;

class Definition extends Route
{
    protected array $bindings = [];

    public function bind(string $name, \Closure $closure): void
    {
        $this->addBinding($name, $closure);
    }

    protected function addBinding(string $name, \Closure $value): void
    {
        $this->bindings[$name] = $value;
    }

    public function __serialize(): array
    {
        $parent = parent::__serialize();
        $parent['bindings'] = array_map(
            fn ($closure) => serializeClosure($closure),
            $this->bindings
        );
    }

    public function __unserialize(array $data): void
    {
        $bindings = $data['bindings'] ?? [];
        unset($data['bindings']);

        parent::__unserialize($data);

        $this->bindings = array_map(
            fn ($serialized) => \Opis\Closure\unserialize($serialized),
            $bindings
        );
    }
}
