<?php

declare(strict_types=1);

namespace Webmaster\Core;

use Webmaster\CoreInterface;

class AutomaticEntityIntegration implements Contract\IntegratesWithCore
{
    public function __invoke(CoreInterface $core): void
    {
        foreach ($this->getEntities() as $entity) {
            $core->registerEntity($entity);
        }

        foreach ($this->getProviders() as $provider) {
            $core->registerProvider($provider);
        }
    }

    public function getEntities(): array
    {
        return [];
    }

    public function getProviders(): array
    {
        return [];
    }

}
