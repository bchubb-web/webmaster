<?php

// src/DependencyInjection/AppExtension.php
namespace Webmaster;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Nette\PhpGenerator\Printer;

class InternalExtension extends AbstractExtension
{
    /**
     * Configures the bundle's configuration tree.
     *
     * @param DefinitionConfigurator $definition The definition configurator
     */
    public function configure(DefinitionConfigurator $definition): void
    {
    }

    public function loadExtension(array $config, ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void
    {
        $containerBuilder->register(Printer::class);
        $containerBuilder->register('webmaster.internal.compiler', \Webmaster\Core\Compiler::class)
            ->setArguments([Printer::class])
            ->setPublic(false)
        ;
    }
}

