<?php

declare(strict_types=1);

namespace Webmaster\Debug\Event;

use DebugBar\DataCollector\TimeDataCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmaster\View\Event;

class ViewEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly TimeDataCollector $timeline,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            Event\RenderStart::class => 'onRenderStart',
            Event\RenderEnd::class => 'onRenderEnd',
        ];
    }

    public function onRenderStart(Event\RenderStart $event): void
    {
        $this->timeline->startMeasure("rendering-$event->view", "Rendering View: $event->view");
    }

    public function onRenderEnd(Event\RenderEnd $event): void
    {
        $this->timeline->stopMeasure("rendering-$event->view");
    }

}
