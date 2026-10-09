<?php

declare(strict_types=1);

namespace Webmaster\Forms\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class FormField
{
    public function __construct(
        public string $type,
        public ?string $label = null,
        public ?string $name = null,
        public bool $required = false,
        public ?string $placeholder = null,
    ) {
    }
}
