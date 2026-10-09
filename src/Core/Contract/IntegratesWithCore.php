<?php

declare(strict_types=1);

namespace Webmaster\Core\Contract;

use Webmaster\CoreInterface;

interface IntegratesWithCore
{
    public function __invoke(CoreInterface $core): void;

    public function getEntities(): array;

    public function getProviders(): array;
}
