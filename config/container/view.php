<?php

declare(strict_types=1);

use League\Container\Container;
use Webmaster\View\Contract\HasViewRenderer;
use Webmaster\View\Contract\ViewRenderer;
use Webmaster\View\Twig\TwigRenderer;

return function (Container $container): void {

    $container
        ->addShared(
            ViewRenderer::class,
            fn ($TwigRenderer) => $TwigRenderer
        )
        ->addArgument(TwigRenderer::class)
    ;

    $container
        ->inflector(HasViewRenderer::class)
        ->invokeMethod('setViewRenderer', [ViewRenderer::class])
    ;
};
