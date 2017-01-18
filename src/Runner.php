<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Console\SymfonyConsole;
use KamranAhmed\Walkers\Storage\JsonStorage;
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
    const MAPS_PATH    = __DIR__ . '/../config/map.php';
    const STORAGE_PATH = __DIR__ . '/../storage';

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
        $io = new SymfonyStyle($input, $output);

        $console = new SymfonyConsole($io);
        $storage = new JsonStorage(static::STORAGE_PATH);
        $map     = new Map(self::MAPS_PATH);

        $map = new Game($console, $storage, $map);
        $map->play();

        return 0;
    }
}
