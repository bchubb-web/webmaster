<?php

declare(strict_types=1);

namespace Webmaster\Blocks\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Template
{
    public function __construct(
        public string $template,
    ) {
    }
}
