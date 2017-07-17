<?php

namespace Copona\Helpers;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Command
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return SymfonyStyle
     */
    public static function prepareIO(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $style = new OutputFormatterStyle('black', 'red', array('bold'));
        $io->getFormatter()->setStyle('fail', $style);

        $style = new OutputFormatterStyle('black', 'green', array('bold'));
        $io->getFormatter()->setStyle('passed', $style);

        return $io;
    }

    public static function makeTableSetting(OutputInterface $output, array $headers = [], array $requirements = [])
    {
        $table = new Table($output);

        $table->setHeaders($headers);

        $rows = [];

        foreach ($requirements as $title => $requirement) {

            if ($requirement['status'] == $requirement['required']) {
                $rows[] = [
                    self::green($title),
                    self::green($requirement['current']),
                    self::green($requirement['required']),
                    self::checkSuccess(''),
                ];
            } else {
                $rows[] = [
                    self::red($title),
                    self::red($requirement['current']),
                    self::red($requirement['required']),
                    self::checkFail(''),
                ];
            }
        }

        $table->setRows($rows);

        return $table;
    }

    public static function makeTablePath(OutputInterface $output, array $headers = [], array $requirements = [])
    {
        $table = new Table($output);

        $table->setHeaders($headers);

        $rows = [];

        foreach ($requirements as $requirement) {

            if ($requirement['exists']) {
                $rows[] = [
                    self::green($requirement['path']),
                    self::green($requirement['exists']),
                    self::checkSuccess(''),
                ];
            } else {
                $rows[] = [
                    self::red($requirement['path']),
                    self::red($requirement['exists']),
                    self::checkFail(''),
                ];
            }
        }

        $table->setRows($rows);

        return $table;
    }

    /**
     * Message in red
     *
     * @param $message
     * @return string
     */
    public static function red($message)
    {
        if (is_bool($message)) {
            $message = $message ? 'On' : 'Off';
        }
        return "<fg=red> " . $message . "</>";
    }

    /**
     * Message in green
     *
     * @param $message
     * @return string
     */
    public static function green($message)
    {
        if (is_bool($message)) {
            $message = $message ? 'On' : 'Off';
        }
        return "<fg=green> " . $message . "</>";
    }

    /**
     * Check like success
     *
     * @param $message
     * @return string
     */
    public static function checkSuccess($message)
    {
        return "<passed> " . "\xF0\x9F\x91\x8D" . "  </passed> <fg=green>" . $message . "</>";
    }

    /**
     * Check unlike Fail
     *
     * @param $message
     * @return string
     */
    public static function checkFail($message)
    {
        return "<fail> " . "\xF0\x9F\x91\x8E" . "  </fail> <fg=red>" . $message . '</>';
    }

    /**
     * Clear screen
     *
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     */
    public static function clear(SymfonyStyle $io, OutputInterface $output)
    {
        $output->write(sprintf("\033\143"));
        $io->write(sprintf("\033\143"));
    }
}