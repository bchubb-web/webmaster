<?php

declare(strict_types=1);

namespace Webmaster;

use League\Config\Configuration;
use Psr\Container\ContainerInterface;

class Core implements CoreInterface
{
    private Configuration $configuration;

    private ContainerInterface $container;

    final public function __construct()
    {
    }

    public function getEntities(): array
    {
        return [];
    }

    public function getConfigDir(): string
    {
        return ROOT . '/config';
    }

    public function getWebmasterConfigDir(): string
    {
        return WEBMASTER . '/config';
    }

    protected function loadConfiguration(): Configuration
    {
        $configInit = require_once $this->getWebmasterConfigDir() . '/config.php';
        $config = $configInit();

        $applySiteConfig = require_once $this->getConfigDir() . '/config.php';
        $config = $applySiteConfig($config);

        return $config;
    }

    public function getConfiguration(): Configuration
    {
        if (!isset($this->configuration)) {
            $this->configuration = $this->loadConfiguration();
        }

        return $this->configuration;
    }


    public function getProviders(): array
    {
        return [];
    }

    public function getContainerDefinition(): string
    {
        return $this->getWebmasterConfigDir() . '/container.php';
    }

    public function getWebmasterProviders(): array
    {
        return [
            'webmaster.twig' => $this->getWebmasterConfigDir() . '/container/twig.php',
        ];
    }

    protected function createContainer(): ContainerInterface
    {
        $config = $this->getConfiguration();

        $init = include $this->getContainerDefinition();

        $container = $init($config);

        foreach ($this->getWebmasterProviders() as $file) {
            if (!is_file($file)) {
                throw new \RuntimeException("Container definition file not found: $file");
            }
            $provider = include $file;
            $container->addServiceProvider($provider);
        }

        foreach ($this->getProviders() as $file) {
            if (!is_file($file)) {
                throw new \RuntimeException("Container definition file not found: $file");
            }
            $provider = include $file;
            $container->addServiceProvider($provider);
        }

        foreach ($this->getEntities() as $entity) {
            $container->add($entity)->addTag('webmaster.db.entity');
        }

        return $container;
    }

    public function getContainer(): ContainerInterface
    {
        if (!isset($this->container)) {
            $this->container = $this->createContainer();
        }

        return $this->container;
    }

    public function getCacheDir(): string
    {
        return ROOT . '/tmp/cache';
    }
}
