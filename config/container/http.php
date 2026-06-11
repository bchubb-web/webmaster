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

return new class extends Provider
{
    protected $identifier = 'webmaster.http';

    public function requires(): array
    {
        return [
        ];
    }

    public function providedServices(): array
    {
        return [];
    }

    public function definitions(Container $container): void
    {
    }
};
