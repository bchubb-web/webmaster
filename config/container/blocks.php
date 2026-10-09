<?php

declare(strict_types=1);

use League\Container\Container;
use Webmaster\DependencyInjection\Provider;

return new class extends Provider
{
    protected $identifier = 'webmaster.blocks';

    public function requires(): array
    {
        return [
        ];
    }

    public function providedServices(): array
    {
        return [
        ];
    }

    public function definitions(Container $container): void
    {
    }
};
