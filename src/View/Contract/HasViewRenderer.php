<?php

declare(strict_types=1);

namespace Webmaster\View\Contract;

interface HasViewRenderer
{
    public function setViewRenderer(ViewRenderer $viewRenderer): void;
    public function getViewRenderer(): ViewRenderer;
}
