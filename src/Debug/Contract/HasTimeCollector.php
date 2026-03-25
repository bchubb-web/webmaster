<?php

declare(strict_types=1);

namespace Webmaster\Debug\Contract;

use DebugBar\DataCollector\TimeDataCollector;

interface HasTimeCollector
{
    public function setTimeCollector(TimeDataCollector $timeCollector): void;
    public function getTimeCollector(): TimeDataCollector;
}
