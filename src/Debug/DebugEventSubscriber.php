<?php

declare(strict_types=1);

namespace Webmaster\Debug;

use DebugBar\DataCollector\TimeDataCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmaster\Http\Event\RoutingPost;
use Webmaster\Http\Event\RoutingPre;

class DebugEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected TimeDataCollector $timeline,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RoutingPre::class => 'onRoutingPre',
            RoutingPost::class => 'onRoutingPost',
        ];
    }

    public function onRoutingPre(RoutingPre $event): void
    {
        $this->timeline->startMeasure('routing', 'Routing');
    }

    public function onRoutingPost(RoutingPost $event): void
    {
        $this->timeline->stopMeasure('routing');
    }
}
