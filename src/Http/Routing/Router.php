<?php

declare(strict_types=1);

namespace Webmaster\Http\Routing;

use DebugBar\DataCollector\TimeDataCollector;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Webmaster\Http\Event\RoutingPost as RoutingPostEvent;
use Webmaster\Http\Event\RoutingPre as RoutingPreEvent;

class Router
{
    public function __construct(
        protected readonly TimeDataCollector $timeline,
        protected RouteBuilder $routeBuilder,
        protected ServerRequestInterface $request,
        protected readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function match(ServerRequestInterface $request): ServerRequestInterface
    {
        $this->eventDispatcher->dispatch(new RoutingPreEvent());

        $matched = $this->getMatcher()->match(
            $request->getUri()->getPath()
        );

        $this->eventDispatcher->dispatch(new RoutingPostEvent($matched));

        $target = $matched['_target'];
        $parameters = $matched;

        unset($parameters['_target']);
        unset($parameters['_route']);

        $request = $request->withAttribute('_target', $target);
        foreach ($parameters as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        // return $request->withAttribute('matched', $matched); dont think this is needed
        return $request;
    }

    protected function getMatcher(): UrlMatcher
    {
        return new CompiledUrlMatcher(
            $this->routeBuilder->getRoutes(),
            new RequestContext(
                baseUrl: '',
                method: $this->request->getMethod(),
                host: $this->request->getUri()->getHost(),
                scheme: $this->request->getUri()->getScheme(),
                path: $this->request->getUri()->getPath(),
                queryString: $this->request->getUri()->getQuery(),
            ),
        );
    }
}
