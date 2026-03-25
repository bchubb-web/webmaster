<?php

declare(strict_types=1);

namespace Webmaster\Debug\Trait;

use DebugBar\DataCollector\TimeDataCollector;

trait CanCollectTime
{
    protected ?TimeDataCollector $timeline = null;

    public function setTimeCollector(TimeDataCollector $timeCollector): void
    {
        $this->timeline = $timeCollector;
    }

    public function getTimeCollector(): TimeDataCollector
    {
        if ($this->timeline === null) {
            throw new \LogicException('Time collector has not been set.');
        }

        return $this->timeline;
    }

    public function addTimeMeasure(string $name, float $start, float $end): void
    {
        $this->getTimeCollector()->addMeasure($name, $start, $end);
    }
}
