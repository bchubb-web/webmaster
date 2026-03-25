<?php

declare(strict_types=1);

namespace Webmaster\View\Contract;

interface ViewRenderer
{
    public function render(string $template, array $data = []): string;
    public function stream(string $template, array $data = []): \Psr\Http\Message\StreamInterface;
}
