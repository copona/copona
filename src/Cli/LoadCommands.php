<?php

namespace Copona\Cli;

use Symfony\Component\Console\Application;

class LoadCommands
{
    public function __construct()
    {
        $classPaths = glob(__DIR__ . '/Commands/*', GLOB_BRACE);
        $classes = [];
        $namespace = 'Copona\Cli\Commands\\';
        foreach ($classPaths as $classPath) {
            $segments = explode('/', $classPath);
            $segments = explode('\\', $segments[count($segments) - 1]);
            $classes[] = rtrim($namespace . $segments[count($segments) - 1], '.php');
        }
        $application = new Application();
        foreach ($classes as $class) {
            $application->add(new $class);
        }

        $application->run();
    }
}