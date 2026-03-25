<?php

declare(strict_types=1);

use League\Container\Container;
use Webmaster\Http\Infra\Session as SessionImpl;
use Webmaster\Http\Domain\Session as SessionContract;

return function (Container $container): void {
    $container
        ->addShared(
            SessionContract::class,
            SessionImpl::class,
        )
        ->addArgument(Psr\Http\Message\ServerRequestInterface::class)
    ;
};
