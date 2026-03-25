<?php

declare(strict_types=1);

namespace Webmaster\View\Trait;

use Webmaster\View\Contract\ViewRenderer;

trait CanRenderViews
{
    protected ?ViewRenderer $viewRenderer = null;

    public function setViewRenderer(ViewRenderer $viewRenderer): void
    {
        $this->viewRenderer = $viewRenderer;
    }

    public function getViewRenderer(): ViewRenderer
    {
        if ($this->viewRenderer === null) {
            throw new \LogicException('View renderer has not been set.');
        }

        return $this->viewRenderer;
    }
}
