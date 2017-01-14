<?php

namespace KamranAhmed\Lost;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Game
 *
 * Responsible for initializing the game
 *
 * @package KamranAhmed\Lost
 */
class Game extends Command
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
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->io->section('Lost - An Adventure Game');
        
    }
}
