<?php

namespace Copona\Cli\Commands;

use Copona\Classes\Install;
use Copona\Classes\Requirements;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends Command
{
    /**
     * Reinstall check
     *
     * @var bool
     */
    protected $reinstall = false;

    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Install Copona');
    }

    /**
     * Install
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->prepareIO($input, $output);

        $io->title('Install Copona');

        if (Install::checkIfInstalled()) {
            $io->writeln('<comment>Project Already installed</comment>');
            $io->newLine(1);

            $helper = $this->getHelper('question');
            $questionReinstall = new ConfirmationQuestion("<error>Do you want to reinstall again? All database will be erased. (y/N)</error>", false);
            $answerReinstall = $helper->ask($input, $output, $questionReinstall);

            if ($answerReinstall) {
                $this->reinstall = true;
                $this->clear($io, $output);
                $this->stepOne($io, $output, $input);
                $output->writeln('<info>Copona successfully installed</info>');
            }

        } else {
            $this->stepOne($io, $output, $input);
            $output->writeln('<info>Copona successfully installed</info>');
        }
    }

    protected function stepOne(SymfonyStyle $io, OutputInterface $output, InputInterface $input)
    {
        $io->section('Check your server is set-up correctly');

        $io->text('1. Please configure your PHP settings to match requirements listed below.');
        $this->tableOne($output);

        $io->newLine(1);
        $io->text('2. Please make sure the PHP extensions listed below are installed.');
        $this->tableTwo($output);

        $io->newLine(1);
        $io->text('3. Please make sure you have set the correct permissions on the files list below.');
        $this->tableThree($output);

        $io->newLine(1);
        $io->text('4. Please make sure you have set the correct permissions on the directories list below.');
        $this->tableFour($output);

        $helper = $this->getHelper('question');
        $questionNextStep = new ConfirmationQuestion("<question>Do you want to continue to the next step? (Y/n)</question>", true);
        $answerNextStep = $helper->ask($input, $output, $questionNextStep);

        if ($answerNextStep) {
            $this->twoStep($io, $output, $input);
        }
    }

    protected function twoStep(SymfonyStyle $io, OutputInterface $output, InputInterface $input)
    {
        $this->clear($io, $output);

        $io->section('Enter your database and administration details');

        $io->text('1. Please enter your database connection details.');

        $data['db_driver'] = $io->choice(
            'DB Driver',
            ['mysqli', 'mPDO'],
            'mysqli'
        );

        $question = new Question('Hostname', 'localhost');
        $data['db_hostname'] = $io->askQuestion($question);

        $question = new Question('Database name');
        $data['db_database'] = $io->askQuestion($question);

        $question = new Question('Username DB', 'root');
        $data['db_username'] = $io->askQuestion($question);

        $question = new Question('Password DB', false);
        $data['db_password'] = $io->askQuestion($question);
        $data['db_password'] = $data['db_password'] ? $data['db_password'] : '';

        $question = new Question('Port', 3306);
        $data['db_port'] = $io->askQuestion($question);

        $question = new Question('Prefix', 'cp_');
        $data['db_prefix'] = $io->askQuestion($question);

        $io->text('2. Please enter a username and password for the administration.');

        $question = new Question('Username');
        $data['username'] = $io->askQuestion($question);

        $data['password'] = $io->askHidden('Password');

        $question = new Question('Email');
        $data['email'] = $io->askQuestion($question);

        $this->threeStep($data, $io, $output);
    }

    protected function threeStep(array $data, SymfonyStyle $io, OutputInterface $output)
    {
        $this->clear($io, $output);

        $io->section('Installing');

        $step_count = $this->reinstall ? 4 : 3;

        $progressBar = new ProgressBar($output, $step_count);

        $progressBar->setFormatDefinition('custom', '%message%' . PHP_EOL . '%current%/%max% -- [%bar%]');
        $progressBar->setFormat('custom');

        $progressBar->setMessage('Creating .env');
        $progressBar->start();

        Install::createDotEnv([
            'APP_ENV'     => 'dev',
            'DB_DRIVER'   => addslashes($data['db_driver']),
            'DB_HOSTNAME' => addslashes($data['db_hostname']),
            'DB_USERNAME' => addslashes($data['db_username']),
            'DB_PASSWORD' => addslashes(html_entity_decode($data['db_password'], ENT_QUOTES, 'UTF-8')),
            'DB_DATABASE' => addslashes($data['db_database']),
            'DB_PORT'     => addslashes($data['db_port']),
            'DB_PREFIX'   => addslashes($data['db_prefix']),
        ]);
        sleep(1);

        if ($this->reinstall) {
            $progressBar->setMessage('Rollback migration');
            $progressBar->advance();
            Install::rollback($data);
            sleep(1);
        }

        $progressBar->setMessage('Creating database structure');
        $progressBar->advance();

        Install::database($data);

        $progressBar->setMessage('Executing migrations');
        $progressBar->advance();

        Install::migration();
        sleep(1);

        $progressBar->setMessage('Finished');
        $progressBar->finish();
        $io->newLine(2);
    }

    protected function tableOne(OutputInterface $output)
    {
        $headers = ['PHP Settings', 'Current Settings', 'Required Settings', 'Status'];

        $requirements = Requirements::getSettingsPHP();

        $table = $this->makeTableSetting($output, $headers, $requirements);
        $table->render();
    }

    protected function tableTwo(OutputInterface $output)
    {
        $headers = ['Extension Settings', 'Current Settings', 'Required Settings', 'Status'];

        $requirements = Requirements::getSettingsExtension();

        $table = $this->makeTableSetting($output, $headers, $requirements);
        $table->render();
    }

    protected function tableThree(OutputInterface $output)
    {
        $headers = ['Files', 'Status'];

        $config_env = Requirements::paths()['config_env'];

        $requirements[] = $config_env;

        $table = $this->makeTablePath($output, $headers, $requirements);
        $table->render();
    }

    protected function tableFour(OutputInterface $output)
    {
        $headers = ['Directories', 'Status'];

        $requirements = Requirements::paths();

        unset($requirements['config_env']);

        $table = $this->makeTablePath($output, $headers, $requirements);
        $table->render();
    }

    protected function makeTableSetting(OutputInterface $output, array $headers = [], array $requirements = [])
    {
        $table = new Table($output);

        $table->setHeaders($headers);

        $rows = [];

        foreach ($requirements as $title => $requirement) {

            if ($requirement['status'] == $requirement['required']) {
                $rows[] = [
                    $this->green($title),
                    $this->green($requirement['current']),
                    $this->green($requirement['required']),
                    $this->checkSuccess(''),
                ];
            } else {
                $rows[] = [
                    $this->red($title),
                    $this->red($requirement['current']),
                    $this->red($requirement['required']),
                    $this->checkFail(''),
                ];
            }
        }

        $table->setRows($rows);

        return $table;
    }

    protected function makeTablePath(OutputInterface $output, array $headers = [], array $requirements = [])
    {
        $table = new Table($output);

        $table->setHeaders($headers);

        $rows = [];

        foreach ($requirements as $requirement) {

            if ($requirement['exists']) {
                $rows[] = [
                    $this->green($requirement['path']),
                    $this->green($requirement['exists']),
                    $this->checkSuccess(''),
                ];
            } else {
                $rows[] = [
                    $this->red($requirement['path']),
                    $this->red($requirement['exists']),
                    $this->checkFail(''),
                ];
            }
        }

        $table->setRows($rows);

        return $table;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return SymfonyStyle
     */
    protected function prepareIO(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $style = new OutputFormatterStyle('black', 'red', array('bold'));
        $io->getFormatter()->setStyle('fail', $style);

        $style = new OutputFormatterStyle('black', 'green', array('bold'));
        $io->getFormatter()->setStyle('passed', $style);

        return $io;
    }

    /**
     * Message in red
     *
     * @param $message
     * @return string
     */
    protected function red($message)
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
    protected function green($message)
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
    protected function checkSuccess($message)
    {
        return "<passed> " . "\xF0\x9F\x91\x8D" . "  </passed> <fg=green>" . $message . "</>";
    }

    /**
     * Check unlike Fail
     *
     * @param $message
     * @return string
     */
    protected function checkFail($message)
    {
        return "<fail> " . "\xF0\x9F\x91\x8E" . "  </fail> <fg=red>" . $message . "</>";
    }

    /**
     * Clear screen
     *
     * @param SymfonyStyle $io
     * @param OutputInterface $output
     */
    protected function clear(SymfonyStyle $io, OutputInterface $output)
    {
        $output->write(sprintf("\033\143"));
        $io->write(sprintf("\033\143"));
    }
}