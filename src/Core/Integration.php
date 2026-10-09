<?php

declare(strict_types=1);

namespace Webmaster\Core;

use Webmaster\CoreInterface;
use Webmaster\Core\Contract\IntegratesWithCore;

class Integration implements Contract\IntegratesWithCore
{
    private IntegratesWithCore $inner;

    public function __construct(IntegratesWithCore $inner)
    {
        $this->inner = $inner;
    }

    public function __invoke(CoreInterface $core): void
    {
        $this->inner->__invoke($core);
    }

    public function getEntities(): array
    {
        return $this->inner->getEntities();
    }

    public function getProviders(): array
    {
        return $this->inner->getProviders();
    }

}
