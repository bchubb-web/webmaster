<?php

declare(strict_types=1);

namespace Webmaster\Entrypoint;

use Middlewares\Debugbar;
use Relay\RelayBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Webmaster\Debug\Contract\HasTimeCollector;
use Webmaster\Debug\Trait\CanCollectTime;
use Webmaster\Http\Middleware\SessionHandler;
use Webmaster\Http\ResponseEmitter;
use Webmaster\Http\Routing\Router;
use Webmaster\Http\Dispatcher;
use function ob_start;

class Web extends AbstractEntrypoint implements HasTimeCollector
{
    use CanCollectTime;

    public function __construct(
        private ServerRequestInterface $request,
        private readonly Router $router,
        private readonly RelayBuilder $relayBuilder,
        private readonly Dispatcher $dispatcher,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly ResponseEmitter $emitter,
    ) {
        ob_start();
    }

    public function handle(): int
    {
        $this->addTimeMeasure('Framework boot', $_SERVER['REQUEST_TIME_FLOAT'], microtime(true));

        if (!is_null($redirectResult = $this->handleRedirects())) {
            return $redirectResult;
        }

        try {
            $this->request = $this->router->match($this->request);

            $queue = [
                $this->container->get(Debugbar::class),
                $this->container->get(SessionHandler::class),
                $this->dispatcher,
            ];
            $relay = $this->relayBuilder->newInstance($queue);

            $response = $relay->handle($this->request);
        } catch (ResourceNotFoundException $e) {
            $response = $this->responseFactory->createResponse(404);
        }

        ob_end_clean();
        $this->emitter->emit($response);

        return 0;
    }

    protected function handleRedirects(): ?int
    {
        $redirectsFile = $this->getRedirectFilePath();

        if (file_exists($redirectsFile)) {
            $redirects = parse_ini_file($redirectsFile, true);

            if (array_key_exists($this->request->getUri()->getPath(), $redirects)) {
                $target = $redirects[$this->request->getUri()->getPath()];

                $response = $this->responseFactory
                    ->createResponse(301)
                    ->withHeader('Location', $target);

                ob_end_clean();
                $this->emitter->emit($response);
                return 0;
            }
        }

        return null;
    }

    protected function getRedirectFilePath(): string
    {
        return ROOT . '/config/redirects.ini';
    }
}
