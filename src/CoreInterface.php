<?php

declare(strict_types=1);

namespace Webmaster;

interface CoreInterface
{
    public function __construct();

    /* db */

    public function getEntities(): array;

    /* container */

    public function getContainerDefinition(): string;

    public function getProviders(): array;

    /* config files */

    public function getConfigDir(): string;

    public function getWebmasterConfigDir(): string;

    /* cache dir */

    public function getCacheDir(): string;
}
