<?php

declare(strict_types=1);

namespace Webmaster\Http\Routing;

use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\TimeDataCollector;
use Symfony\Component\Routing\RouteCollection;
use Webmaster\Http\Routing\Cache\RedisCache;
use Webmaster\Http\Method;

class RouteBuilder
{
    // compiled routes
    protected array $routes = [];

    public function __construct(
        protected readonly RedisCache $cache,
        protected readonly RouteCollection $rawRoutes,
        protected readonly TimeDataCollector $timeline,
        protected readonly MessagesCollector $messages,
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
        array $requirements = [],

    ): Definition {

        $uri = rtrim($uri, '/');

        $route = new Definition(
            $uri,
            ['_target' => $target],
            $requirements,
            [],
            '',
            [],
            $methods,
        );

        $this->rawRoutes->add(
            $name ?? spl_object_id($route),
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

    public function getRoutes(): array
    {
        $start = microtime(true);
        if (0 === count($this->routes)) {
            if (null === $cached = $this->cache->get()) {
                $this->build();
                $this->timeline->addMeasure('Build routes', $start, microtime(true));
            } else {
                $this->routes = $cached;
                $this->timeline->addMeasure('Retrieve cached routes', $start, microtime(true));

            }
        }

        $this->messages->addMessage($this->routes, 'Routes');

        return $this->routes;
    }

    public function getGeneratorRoutes(): ?array
    {
        return $this->cache->getGeneratorRoutes();
    }
}
