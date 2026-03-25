<?php

declare(strict_types=1);

namespace Webmaster\DependencyInjection\Contract;

interface ServiceRegistrationContract
{
    public function resolve(): object;
}
