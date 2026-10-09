<?php

declare(strict_types=1);

namespace Webmaster\Core\Contract;

interface SiteDefinition
{
    public function __construct(
        string $name,
    );
}
