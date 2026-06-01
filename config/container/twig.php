<?php

declare(strict_types=1);

use League\Container\Container;
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
use Webmaster\DependencyInjection\Provider;


return new class extends Provider
{
    protected $identifier = 'webmaster.twig';

    public function requires(): array
    {
        return [
            Configuration::class,
        ];
    }

    public function providedServices(): array
    {
        return [
            Environment::class,
            LoaderInterface::class,
            ContainerRuntimeLoader::class,
        ];
    }

    public function register(): void
    {
        // before registering, ensure everything required is also registered
        $container = $this->getContainer();

        foreach ($this->requires() as $requirement) {
            $this->getContainer()->get($requirement);
        }

        $this->definitions($this->getContainer());
    }

    public function definitions(Container $container): void
    {
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

