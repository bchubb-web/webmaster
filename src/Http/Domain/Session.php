<?php

declare(strict_types=1);

namespace Webmaster\Http\Domain;

interface Session extends \ArrayAccess, \JsonSerializable, \Stringable
{
    public function __set(string $key, mixed $value): void;

    public function set(string $key, mixed $value): void;

    public function __get(string $key): mixed;

    public function get(string $key, mixed $default = null): mixed;

    public function getCookieName(): string;
}
