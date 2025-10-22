<?php

declare(strict_types=1);

namespace Webmaster\Http\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Webmaster\Http\Session;
use Webmaster\Http\Session\CookieDriver;

class SessionHandler implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly Session $session,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response->withHeader('Set-Cookie', $this->getSessionHeader());
    }

    protected function getSessionHeader(): string
    {
        return Session::COOKIE_NAME . '=' . $this->session->__toString();
    }
}
