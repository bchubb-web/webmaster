<?php

namespace Webmaster\Http\Session;

use Psr\Http\Message\ServerRequestInterface;

class CookieDriver implements SessionDriverInterface
{
    private ServerRequestInterface $request;

    public function __construct(
    ) {
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getCookieHeader(): string
    {
        // Example implementation, replace with actual logic
        return 'session_id=abc123; Path=/; HttpOnly';
    }
}
