<?php

declare(strict_types=1);

namespace Webmaster\View\Twig;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Twig\Environment;
use Webmaster\View\Contract\ViewRenderer;
use Webmaster\View\Event;

class TwigRenderer implements ViewRenderer
{
    public function __construct(
        private readonly Environment $twig,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function render(string $template, array $data = []): string
    {
        $this->eventDispatcher->dispatch(new Event\RenderStart($template));

        if (!$this->twig->getLoader()->exists($template)) {
            throw new \RuntimeException("Template '$template' not found.");
        }

        $output = $this->twig->render($template, $data);

        $this->eventDispatcher->dispatch(new Event\RenderEnd($template));

        return $output;
    }

    public function stream(string $template, array $data = []): StreamInterface
    {
        return $this->streamFactory->createStream(
            $this->render($template, $data)
        );
    }

    public function response(int $status, string $template, array $data = []): ResponseInterface
    {
        return $this
            ->responseFactory
            ->createResponse($status)
            ->withBody($this->stream($template, $data))
        ;
    }
}
