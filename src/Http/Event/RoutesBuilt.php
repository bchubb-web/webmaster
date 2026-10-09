<?php

declare(strict_types=1);

namespace Webmaster\Http\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RoutesBuilt extends Event
{
    public function __construct(
        public array $routes = [],
        public float $startTime = 0.0,
        public bool $cached = false,
    ) {}
}
