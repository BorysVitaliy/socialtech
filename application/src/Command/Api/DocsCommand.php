<?php

declare(strict_types=1);

namespace App\Command\Api;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DocsCommand extends Command
{
    private const SWAGGER_BIN_PATH = 'vendor/bin/openapi';
    private const SWAGGER_SOURCE = 'src/Controller';
    private const SWAGGER_TARGET = 'public/docs/openapi.json';

    protected function configure(): void
    {
        $this
            ->setName('api:docs')
            ->setDescription('Generates OpenAPI docs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = new Process([
            PHP_BINARY,
            self::SWAGGER_BIN_PATH,
            self::SWAGGER_SOURCE,
            '--output',
            self::SWAGGER_TARGET
        ]);

        $process->run(static function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        $output->writeln('<info>Done!</info>');

        return 1;
    }
}
