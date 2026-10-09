<?php

namespace Webmaster\Http\Routing\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmaster\Http\Routing\Cache\RedisCache;

class ClearRouteCache extends Command
{
    protected static $defaultName = 'route:clear-cache';

    public function __construct(
        protected readonly RedisCache $cache,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this->setDescription('Clears the route cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return match ($this->cache->clear()) {
            true => Command::SUCCESS,
            false => Command::FAILURE,
        };
    }
}
