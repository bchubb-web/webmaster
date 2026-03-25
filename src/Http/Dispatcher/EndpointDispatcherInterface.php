<?php

declare(strict_types=1);

namespace Webmaster\Http\Dispatcher;

use Psr\Http\Message\ResponseInterface;

interface EndpointDispatcherInterface
{
    public function dispatch(): ResponseInterface;
}
