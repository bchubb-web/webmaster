<?php

namespace Webmaster\SimplePage;

use Webmaster\View\Trait\CanRenderViews;

trait CanRenderSimplePages
{
    use CanRenderViews;

    public function page(string $template, array $data = []): string
    {
        return $this->getTemplateRenderer()->render($template, $data);
    }
}
