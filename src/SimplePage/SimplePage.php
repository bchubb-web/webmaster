<?php

declare(strict_types=1);

namespace Webmaster\SimplePage;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Webmaster\View\Contract\ViewRenderer;
use Webmaster\View\Trait\CanRenderViews;

class SimplePage implements ResponseInterface, ContainerAwareInterface
{
    use CanRenderViews;
    use ContainerAwareTrait;

    protected ?ResponseInterface $response = null;
    protected Config $config;

    public function __construct(
        protected string $template = 'index',
        protected array $data = [],
        ?ContainerInterface $container = null,
    )
    {
        $this->setContainer($container);
        $this->setViewRenderer($this->getContainer()->get(ViewRenderer::class));

        $this->response = $this->getContainer()
            ->get(ResponseFactoryInterface::class)
            ->createResponse(200)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($this->getViewRenderer()->stream($this->template, $this->data))
        ;

        $this->config = $this->getContainer()->get(Config::class);
    }

    protected function getResponse(): ResponseInterface
    {
        return $this->response ??= $this->getContainer()->get(ResponseInterface::class);
    }


    public function getProtocolVersion(): string
    {
        return $this->getResponse()->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->response = $this->getResponse()->withProtocolVersion($version);
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->getResponse()->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->getResponse()->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->getResponse()->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->getResponse()->getHeaderLine($name);
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $this->response = $this->getResponse()->withHeader($name, $value);
        return $this;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $this->response = $this->getResponse()->withAddedHeader($name, $value);
        return $this;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $this->response = $this->getResponse()->withoutHeader($name);
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return $this->getResponse()->getBody();
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->response = $this->getResponse()->withBody($body);
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->getResponse()->getStatusCode();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->response = $this->getResponse()->withStatus($code, $reasonPhrase);
        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->getResponse()->getReasonPhrase();
    }
}
