<?php

declare(strict_types=1);

namespace Webmaster\Http;

use Psr\Http\Message\ServerRequestInterface;

class Session implements \ArrayAccess, \JsonSerializable, \Stringable
{
    protected array $data = [];

    public const COOKIE_NAME = 'WEBMASTER_SESSION_ID';

    public function __construct(
        private readonly ServerRequestInterface $request,
    ) {
        $json = $this->request->getCookieParams()[self::COOKIE_NAME] ?? [];

        $this->data = is_string($json) ? json_decode($json, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}

