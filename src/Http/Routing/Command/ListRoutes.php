<?php

namespace Webmaster\Http\Routing\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmaster\Http\Routing\Cache\RedisCache;
use Webmaster\Http\Routing\RouteBuilder;
use Symfony\Component\Console\Helper\Table;

class ListRoutes extends Command
{
    protected static $defaultName = 'route:list';

    public function __construct(
        protected readonly RouteBuilder $routeBuilder,
        protected readonly RedisCache $cache,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this
            ->setDescription('Lists all registered routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cache->clear();
        $this->routeBuilder->build();

        $routes = $this->routeBuilder->getGeneratorRoutes();

        dd($routes);

        $table = new Table($output);
        $table->setHeaders(['Name', 'URI', 'Target']);

        foreach ($routes as $name => $route) {
            if (!$route) {
                continue;
            }
            if (is_array($route[1]['_target'])) {
                $target = implode(',', $route[1]['_target']);
            } else {
                $target = $route[1]['_target'];
            }

            $uri = $route[3][0][1];

            $table->addRow([$name, $uri, $target]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
