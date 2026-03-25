<?php

namespace Webmaster\Http\Routing\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmaster\Http\Routing\RouteBuilder;
use Symfony\Component\Console\Helper\Table;

class ListRoutes extends Command
{
    protected static $defaultName = 'route:list';

    public function __construct(
        protected readonly RouteBuilder $routeBuilder,
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
        $this->routeBuilder->build();

        $routes = $this->routeBuilder->getRoutes();

        $table = new Table($output);
        $table->setHeaders(['Method', 'URI', 'Target']);

        foreach ($routes as $route) {
            if (!$route) {
                continue;
            }
            $methods = implode('|', $route->getMethods());
            $uri = $route->getPath();
            $target = is_array($route->getDefault('_target')) ? implode(',', $route->getDefault('_target')) : $route->getDefault('_target');

            $table->addRow([$methods, $uri, $target]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
