<?php

declare(strict_types=1);

namespace Webmaster\Http\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RoutingPost extends Event
{
    public function __construct(
        public array &$matched = [],
    ) {}
}
