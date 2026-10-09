<?php

declare(strict_types=1);

namespace Webmaster\Http\Routing;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouteCollection;
use Webmaster\Http\Event\RoutesBuilt;
use Webmaster\Http\Routing\Cache\RedisCache;
use Webmaster\Http\Method;

class RouteBuilder
{
    // compiled routes
    protected array $routes = [];

    public function __construct(
        protected readonly RedisCache $cache,
        protected readonly RouteCollection $rawRoutes,
        protected readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @param string|array<string> $target
     * @param array<Method> $methods
     */
    public function add(
        string $uri,
        string|array $target,
        array $methods = [Method::GET, Method::POST],
        ?string $name = null,
        array $defaults = [],
        array $requirements = [],

    ): Definition {

        $uri = rtrim($uri, '/');

        $defaults = array_merge($defaults, ['_target' => $target]);
        $route = new Definition(
            path: $uri,
            defaults: $defaults,
            requirements: $requirements,
            methods: $methods,
        );

        $this->rawRoutes->add(
            $name ?? (string) spl_object_id($route),
            $route
        );

        return $route;
    }

    public function build(): void
    {
        $routesFile = ROOT . '/config/http/routes.php';

        if (file_exists($routesFile)) {
            $loader = require $routesFile;

            if (is_callable($loader)) {
                $loader($this);
            }
        }

        $this->cache->set($this->rawRoutes);
        $this->routes = $this->cache->get();

    }

    public function getRawRoutes(): RouteCollection
    {
        return $this->rawRoutes;
    }

    public function getRoutes(): array
    {
        $cached = null;
        $start = microtime(true);
        if (0 === count($this->routes)) {
            if (null === $cached = $this->cache->get()) {
                $this->build();
            } else {
                $this->routes = $cached;
            }
        }

        $this->eventDispatcher->dispatch(new RoutesBuilt(
            routes: $this->routes,
            startTime: $start,
            cached: $cached !== null,
        ));

        return $this->routes;
    }

    public function getGeneratorRoutes(): ?array
    {
        return $this->cache->getGeneratorRoutes();
    }
}
