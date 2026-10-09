<?php

declare(strict_types=1);

namespace Webmaster\SimplePages\Http;

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
use Webmaster\View\Trait\CanRenderViews;
use Webmaster\View\Contract\HasViewRenderer;
use Webmaster\SimplePages\Entity\SimplePage;
use Webmaster\View\Twig\TwigRenderer;

class ManagementController implements ContainerAwareInterface, HasViewRenderer
{
    use CanRenderViews;
    use ContainerAwareTrait;

    protected EntityRepository $simplePageRepository;

    protected string $formPrefix = 'simple_pages';

    public function __construct(
        protected EntityManager $em,
        protected ResponseFactoryInterface $responseFactory,
        TwigRenderer $viewRenderer,
    ) {
        $this->setViewRenderer($viewRenderer);
        $this->simplePageRepository = $this->em->getRepository(SimplePage::class);
    }

    public function home(): ResponseInterface
    {
        $pages = $this->em->getRepository(SimplePage::class)->findAll();
        return $this->responseFactory->createResponse()
            ->withHeader('Content-Type', 'text/html')
            ->withBody(
                $this->getViewRenderer()->stream(
                    'webmaster/simple-pages/management/home.twig',
                    data: compact('pages')
                )
            )
        ;
    }

    public function edit(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $slug = $request->getAttribute('slug');
        $page = $this->getPageBySlug($slug);

        if (!is_null($slug) && !$page) {
            return $this->responseFactory->createResponse(404)
                ->withHeader('Content-Type', 'text/html')
                ->withBody(
                    Stream::create('Page not found')
                )
            ;
        }

        $form = $page->getForm(
            $this->getFormBuilder('/manage/edit' . ($slug ? '/' . $slug : ''))
        );

        $form->handleRequest();

        if ('POST' === $request->getMethod()) {
            $formData = $request->getParsedBody()[$this->formPrefix] ?? [];
            //$form->submit($formData);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    foreach ($formData as $key => $value) {
                        $page->$key = $value ?? null;
                    }
                    $page->slug = $page->slug ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $page->title)));

                    $this->em->persist($page);
                    $this->em->flush();
                } catch (\Throwable $e) {
                    dd($e->getMessage());
                    return $this->responseFactory->createResponse(500);
                }

                return $this->responseFactory->createResponse(302)
                    ->withHeader('Location', '/manage/edit/' . $page->slug)
                ;
            }
        }
        $form->setData([
            'title' => $page->title,
            'slug' => $page->slug,
            // Add other fields as needed
        ]);

        return $this->renderPage('edit.twig', [
            'page' => $page,
            'form' => $form->createView(),
        ]);
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

    protected function getFormBuilder(string $action): FormBuilderInterface
    {
        $builder = Forms::createFormFactory()->createNamedBuilder($this->formPrefix);
        $builder->setAction($action);
        $builder->setMethod('POST');
        // set htmx attributes for htmx form submission
        $builder->setAttribute('hx-post', $action);
        $builder->setAttribute('hx-target', 'body');

        return $builder;
    }

    protected function getPageBySlug(?string $slug): ?SimplePage
    {
        return match ($slug) {
            null => new SimplePage(),
            default => $this
                ->simplePageRepository
                ->findOneBy(['slug' => $slug]),
        };
    }
}
