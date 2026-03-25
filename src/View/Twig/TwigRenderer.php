<?php

declare(strict_types=1);

namespace Webmaster\View\Twig;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Twig\Environment;
use Webmaster\Debug\Contract\HasTimeCollector;
use Webmaster\Debug\Trait\CanCollectTime;
use Webmaster\View\Contract\ViewRenderer;

class TwigRenderer implements ViewRenderer, HasTimeCollector
{
    use CanCollectTime;

    public function __construct(
        private readonly Environment $twig,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }
    public function render(string $template, array $data = []): string
    {
        $this->getTimeCollector()->startMeasure("rendering-$template", "Rendering $template");
        if (!$this->twig->getLoader()->exists($template)) {
            throw new \RuntimeException("Template '$template' not found.");
        }

        $output = $this->twig->render($template, $data);

        $this->getTimeCollector()->stopMeasure("rendering-$template");

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
