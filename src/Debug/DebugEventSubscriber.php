<?php

declare(strict_types=1);

namespace Webmaster\Debug;

use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\TimeDataCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmaster\Core\Event\Boot;
use Webmaster\Http\Event\DispatchPost;
use Webmaster\Http\Event\DispatchPre;
use Webmaster\Http\Event\HandleRequestPost;
use Webmaster\Http\Event\HandleRequestPre;
use Webmaster\Http\Event\RoutesBuilt;
use Webmaster\Http\Event\RoutingPost;
use Webmaster\Http\Event\RoutingPre;

class DebugEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly MessagesCollector $messages,
        protected readonly TimeDataCollector $timeline,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            Boot::class => 'onBoot',
            RoutesBuilt::class => 'onRoutesBuilt',
            RoutingPre::class => 'onRoutingPre',
            RoutingPost::class => 'onRoutingPost',
            DispatchPre::class => 'onDispatchPre',
            DispatchPost::class => 'onDispatchPost',
            HandleRequestPre::class => 'onHandleRequestPre',
            HandleRequestPost::class => 'onHandleRequestPost',
        ];
    }

    public function onBoot(Boot $event): void
    {
        $this->timeline->addMeasure('Framework Boot', $_SERVER['REQUEST_TIME_FLOAT'], microtime(true));
    }

    public function onRoutesBuilt(RoutesBuilt $event): void
    {
        $message = $event->cached ? 'Routes Loaded from Cache' : 'Routes Built';
        $this->timeline->addMeasure($message, $event->startTime, microtime(true));
        $this->messages->addMessage($event->routes, 'Routes');
    }

    public function onRoutingPre(RoutingPre $event): void
    {
        $this->timeline->startMeasure('routing', 'Routing');
    }

    public function onRoutingPost(RoutingPost $event): void
    {
        $this->timeline->stopMeasure('routing');
    }

    public function onDispatchPre(): void
    {
        $this->timeline->startMeasure('dispatch', 'Dispatch');
    }

    public function onDispatchPost(): void
    {
        $this->timeline->stopMeasure('dispatch');
    }

    public function onHandleRequestPre(): void
    {
        $this->timeline->startMeasure('handle_request', 'Handle Request');
    }

    public function onHandleRequestPost(): void
    {
        $this->timeline->stopMeasure('handle_request');
    }
}
