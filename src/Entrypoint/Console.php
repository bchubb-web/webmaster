<?php

declare(strict_types=1);

namespace Webmaster\Entrypoint;

use Composer\InstalledVersions;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Console\Application;

class Console extends AbstractEntrypoint
{
    protected Application $app;

    public function __construct(
        private readonly EntityManager $entityManager,
    ) {
        $this->app = new Application('Webmaster Site Console', InstalledVersions::getVersion('bchubb-web/webmaster') ?: '0.0.0');
    }

    public function handle(): int
    {
        // add doctrine commands
        ConsoleRunner::addCommands($this->app, new SingleManagerProvider($this->entityManager));

        $commands = [
            // Register your console commands here
        ];

        $this->app->addCommands($commands);

        return $this->app->run();
    }
}
