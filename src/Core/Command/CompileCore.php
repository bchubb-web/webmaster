<?php

declare(strict_types=1);

namespace Webmaster\Core\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmaster\CoreInterface;
use Webmaster\Core\Compiler;

class CompileCore extends Command
{
    protected static $defaultName = 'core:compile';

    public function __construct(
        private CoreInterface $core,
        private Compiler $compiler
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setName(self::$defaultName);
        $this
            ->setDescription('Compiles the core configuration and caches it for faster performance.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $class = ($this->compiler)($this->core);

        $output->writeln("<info>Core compiled successfully.</info>");
        $output->writeln("<info>Compiled class: {$class}</info>");

        // write the compiled class to a file
        $compiledFile = ROOT . '/tmp/compiled-core.php';
        $res = file_put_contents($compiledFile, $class);

        if ($res === false) {
            $output->writeln("<error>Failed to write compiled core to file: {$compiledFile}</error>");
            return Command::FAILURE;
        }
        $output->writeln("<info>Compiled core written to file: {$compiledFile}</info>");

        return Command::SUCCESS;
    }
}
