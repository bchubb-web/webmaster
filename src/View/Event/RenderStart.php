<?php

declare(strict_types=1);

namespace Webmaster\View\Event;

use Symfony\Contracts\EventDispatcher\Event;

class RenderStart extends Event
{
    public function __construct(
        public string $view = '',
    ) {
    }
}
