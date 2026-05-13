<?php

namespace Webmaster\DependencyInjection;

use League\Container\ServiceProvider\AbstractServiceProvider;

abstract class Provider extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        $provider = clone($this);
        $provider->setContainer(new Container);
        $provider->register();
        return $provider->getContainer()->has($id);
    }
}
