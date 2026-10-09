<?php

namespace Webmaster\Http\Throwable;

class NotFound extends \RuntimeException
{
    public function __construct(string $message = "Not Found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
