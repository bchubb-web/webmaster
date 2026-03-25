<?php

declare(strict_types=1);

namespace Webmaster\Entrypoint\Event;

use Symfony\Contracts\EventDispatcher\Event;

class FrameworkBootPre extends Event
{
    public function __construct()
    {
    }
}
