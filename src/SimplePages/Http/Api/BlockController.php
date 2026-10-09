<?php

declare(strict_types=1);

namespace Webmaster\SimplePages\Http\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormBuilderInterface;
use Webmaster\Blocks\RenderWrapper;
use Webmaster\SimplePages\Blocks\Entity\Block;
use Webmaster\View\Trait\CanRenderViews;
use Webmaster\View\Contract\HasViewRenderer;
use Webmaster\SimplePages\Entity\SimplePage;

class BlockController implements ContainerAwareInterface, HasViewRenderer
{
    use CanRenderViews;
    use ContainerAwareTrait;

    protected EntityRepository $simplePageRepository;

    protected string $formPrefix = 'simple_pages';

    public function __construct(
        protected EntityManager $em,
        protected ResponseFactoryInterface $responseFactory,
    ) {
        $this->simplePageRepository = $this->em->getRepository(SimplePage::class);
    }

    public function get(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $block = new Block();
        $block->data = ['text' => ''];
        $block->blockClassName = $request->getAttribute('blockClassName');

        $blockRenderer = new RenderWrapper(
            block: $block,
            viewRenderer: $this->getViewRenderer(),
        );

        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/html')
            ->withBody(
                Stream::create((string)$blockRenderer)
            )
        ;
    }

    protected function renderPage(string $template, array $data = []): ResponseInterface
    {
        $template = 'webmaster/simple-pages/management/' . $template;
        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/html')
            ->withBody(
                $this->getViewRenderer()->stream($template, data: $data)
            )
        ;
    }
}
