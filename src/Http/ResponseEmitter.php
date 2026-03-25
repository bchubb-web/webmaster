<?php

declare(strict_types=1);

namespace Webmaster\Http;

use Psr\Http\Message\ResponseInterface;

class ResponseEmitter
{
    public function emit(ResponseInterface $response): void
    {
        header(sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        echo $response->getBody();
    }
}
