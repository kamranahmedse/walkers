<?php

namespace KamranAhmed\Walkers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Game
 *
 * Responsible for initializing the game
 *
 * @package KamranAhmed\Walkers
 */
class Runner extends Command
{
    protected $io;

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('lost');
    }

    /**
     * Executes the game command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Lost - An Adventure Game');

        $this->io->ask('What are you doing');
    }
}
