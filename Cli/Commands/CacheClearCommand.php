<?php

namespace Copona\Cli\Commands;

use Copona\Helpers\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear cache');
    }

    /**
     * Clear cache
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = [
            DIR_PUBLIC . '/' . \Config::get('image_cache_path'),
        ];

        foreach ($paths as $path) {
            Util::recursiveRemove($path);
        }

        $output->writeln('Cache successfully clean.');

        return true;
    }
}