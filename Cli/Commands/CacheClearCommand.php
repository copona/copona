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
        $paths = [];
        if(\Config::get('image_cache_path')) {
            $paths[] = DIR_PUBLIC . '/' . \Config::get('image_cache_path');
        }

        $paths[] = DIR_PUBLIC . '/storage/private/cache/files/';
        $paths[] = DIR_PUBLIC . '/storage/private/cache/twig/';
        $paths[] = DIR_PUBLIC . '/storage/private/cache/vqmod/';
        $paths[] = DIR_PUBLIC . '/storage/private/cache/';

        foreach ($paths as $path) {
            Util::recursiveRemove($path);
        }

        $output->writeln('<info>Cache successfully clean.</info>');

        return true;
    }
}