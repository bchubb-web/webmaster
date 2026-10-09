<?php

declare(strict_types=1);

namespace Webmaster\Entrypoint;

use Psr\EventDispatcher\EventDispatcherInterface;
use Webmaster\Core;
use Webmaster\Core\Event\Boot as BootEvent;

class Proxy implements EntrypointInterface
{
    private EntrypointInterface $entrypoint;

    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {}

    public function setEntrypoint(EntrypointInterface $entrypoint): void
    {
        $this->entrypoint = $entrypoint;
    }

    public function handle(): int
    {
        $this->eventDispatcher->dispatch(new BootEvent());

        return $this->entrypoint->handle();
    }

    public function getCore(): Core
    {
        return $this->entrypoint->getCore();
    }

    public function setCore(Core $core): void
    {
        $this->entrypoint->setCore($core);
    }
}
