<?php

declare(strict_types=1);

namespace Webmaster\Http;

use DebugBar\DataCollector\TimeDataCollector;
use Nyholm\Psr7\Stream;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function class_implements;
use function is_object;

class Dispatcher implements RequestHandlerInterface
{
    private array $matched = [];

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly TimeDataCollector $timeline,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $target = $request->getAttribute('_target');

        $this->timeline->startMeasure('dispatch', 'Dispatching');

        if (is_array($target)) {
            $callable = $this->container->get($target[0]);
            $method = $target[1];
            $target = [$callable, $method];
        } else if (is_string($target) && $this->container->has($target)) {
            $target = $this->container->get($target);
        }

        $this->timeline->startMeasure('request-handler', 'Handle Request');
        // hanlde callable, then requesthandler interface
        if (is_callable($target)) {
            $args = $this->resolveArgumentsForTarget($target, $request);
            $response = call_user_func_array($target, $args);
        } else if (!is_array($target) && in_array(RequestHandlerInterface::class, class_implements($target))) {
            if (!is_object($target)) {
                $instance = $this->container->get($target);
            } else {
                $instance = $target;
            }
            $response = $instance->handle($request);
        } else {
            throw new \RuntimeException('Cannot dispatch request, target is not callable or a RequestHandlerInterface');

        }

        $this->timeline->stopMeasure('request-handler');

        $this->timeline->stopMeasure('dispatch');

        if (is_string($response)) {
            $response = $this
                ->responseFactory
                ->createResponse(200)
                ->withHeader('Content-Type', 'text/html')
                ->withBody(Stream::create($response))
            ;
        }

        return $response;
    }

    protected function resolveArgumentsForTarget(callable $target, ServerRequestInterface $request): array
    {
        $reflection = new \ReflectionFunction(\Closure::fromCallable($target));
        $parameters = $reflection->getParameters();
        $args = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type->__toString() === ServerRequestInterface::class) {
                $args[] = $request;
            } elseif ($type && !$type->isBuiltin() && $this->container->has($type->getName())) {
                $args[] = $this->container->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            } else {
                throw new \RuntimeException('Cannot resolve parameter ' . $parameter->getName());
            }
        }

        return $args;
    }
}
