<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection\Contract;

use League\Container\Container;
use League\Container\ServiceProvider\ServiceProviderInterface;

interface ProviderContract extends ServiceProviderInterface
{
    public function requires(): array;

    public function definitions(Container $container): void;
}
