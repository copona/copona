<?php

namespace Copona\Cli\Commands;

use Copona\Core\Helpers\Util;
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
     * Limpa os caches
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $paths = [
//            DIR_CACHE,
//            DIR_VQMOD_STORAGE,
//            DIR_VQMOD_CACHE,
//            DIR_IMAGE . 'cache/'
//        ];
//
//        foreach ($paths as $path) {
//            Util::recursiveRemove($path);
//        }

//        $output->writeln('Cache successfully clean.');

        $output->writeln(Util::pathRoot());

        return true;
    }
}