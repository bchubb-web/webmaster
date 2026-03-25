<?php

declare(strict_types=1);

namespace Webmaster\Debug\DataCollector;

use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;
use Webmaster\Http\Domain\Session;

class SessionCollector extends DataCollector implements Renderable
{
    public function __construct(
        protected Session $session,
    ) {
    }

    public function collect(): array
    {
        return $this->session->jsonSerialize();
    }

    public function getName(): string
    {
        return 'session';
    }

    public function getWidgets(): array
    {
        return [
            'session' => [
                'icon' => 'archive',
                'widget' => 'PhpDebugBar.Widgets.VariableListWidget',
                'map' => 'session',
                'default' => '{}',
            ],
        ];
    }
}
