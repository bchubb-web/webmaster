<?php

declare(strict_types=1);

use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\ContainerRuntimeLoader;
use Twig\Loader\LoaderInterface;
use League\Config\Configuration;


use Symfony\UX\TwigComponent\Twig\ComponentExtension;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\UX\TwigComponent\ComponentTemplateFinder;
use Symfony\UX\TwigComponent\ComponentTemplateFinderInterface;
use Symfony\UX\TwigComponent\Twig\ComponentLexer;


return new class extends AbstractServiceProvider
{
    public function provides(string $id): bool
    {
        return in_array($id, [
            Environment::class,
            LoaderInterface::class,
            ContainerRuntimeLoader::class,
        ]);
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container
            ->add(ContainerRuntimeLoader::class)
            ->addArgument(ContainerInterface::class)
        ;

        $container
            ->addShared(
                Environment::class,
            )
            ->addArguments([LoaderInterface::class, [
                'strict_variables' => true,
                'cache' => ROOT . '/tmp/cache',
                'debug' => true,
            ]])
            ->addMethodCall('addRuntimeLoader', [ContainerRuntimeLoader::class])
            ->addMethodCall('addExtension', [ComponentExtension::class])
        ;

        $container
            ->addShared(LoaderInterface::class, function (Configuration $config) {
                return new FilesystemLoader($config->get('view.load_from', []));
            })
            ->addArgument(Configuration::class)
        ;

        $container
            ->add(ComponentLexer::class)
            ->addArgument(Environment::class)
        ;

        $container
            ->addShared(ServiceLocator::class)
            ->addArgument([]);
        ;

        $container
            ->addShared(
                ComponentTemplateFinderInterface::class,
                ComponentTemplateFinder::class,
            )
            ->addArgument(LoaderInterface::class)
            ->addArgument(ROOT . '/views/components')
        ;

        $container
            ->addShared(
                PropertyAccessorInterface::class,
                PropertyAccessor::class,
            )
            ->addArgument(PropertyAccessor::MAGIC_GET|PropertyAccessor::MAGIC_SET)
        ;


    }
};

