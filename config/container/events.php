<?php

declare(strict_types=1);

use League\Container\Container;

return function (Container $container): void {
    $container
        ->addShared(
            Symfony\Component\EventDispatcher\EventDispatcher::class,
        )
        ->addMethodCall('addSubscriber', [
            Webmaster\Debug\DebugEventSubscriber::class,
        ])
    ;

    $container
        ->addShared(
            Psr\EventDispatcher\EventDispatcherInterface::class,
            fn ($dispatcher) => $dispatcher,
        )
        ->addArgument(Symfony\Component\EventDispatcher\EventDispatcher::class)
    ;

    $container
        ->addShared(
            Symfony\Contracts\EventDispatcher\EventDispatcherInterface::class,
            fn ($dispatcher) => $dispatcher,
        )
        ->addArgument(Symfony\Component\EventDispatcher\EventDispatcher::class)
    ;
};
