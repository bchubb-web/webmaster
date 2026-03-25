<?php

declare(strict_types=1);

namespace Webmaster\Http\Dispatcher;

use Psr\Http\Message\ResponseInterface;

class CallableDispatcher implements EndpointDispatcher
{
    public function dispatch(): ResponseInterface
    {

    }
}
