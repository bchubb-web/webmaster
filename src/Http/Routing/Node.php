<?php

declare(strict_types=1);

namespace Webmaster\Http\Routing;

class Node
{
    public function __construct(
        public string $path,
        public string $target,
        public array $methods = ['GET'],
        public string $name = '',
        public array $subnodes = [],
    ) {
    }
}
