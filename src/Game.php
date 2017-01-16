<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Exceptions\InvalidLevelException;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Map
 *
 * @package KamranAhmed\Walkers
 */
class Game
{
    /** @var int */
    protected $level;

    /** @var SymfonyStyle */
    protected $io;

    /** @var Player */
    protected $player;

    /** @var array */
    protected $levelDetail;

    /** @var GameStorage $storage */
    protected $storage;

    const SAVE_EXIT = 'Save and Exit';
    const EXIT      = 'Exit';

    protected $actions = [
        self::SAVE_EXIT,
        self::EXIT,
    ];

    /**
     * Map constructor.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle       $io
     * @param \KamranAhmed\Walkers\Storage\Interfaces\GameStorage $storage
     *
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function __construct(SymfonyStyle $io, GameStorage $storage)
    {
        $this->io      = $io;
        $this->storage = $storage;
    }

    public function play()
    {
        $this->initialize();
        $this->gameLoop();
        $this->endGame();
    }

    public function performAction($action)
    {
        switch ($action) {
            case static::SAVE_EXIT:
                $this->saveGame();
                $this->io->success('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
                exit(0);
            case static::EXIT:
                $this->io->success('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
                exit(0);
        }
    }

    public function saveGame()
    {
        $this->storage->saveGame(
            $this->player->toArray(),
            $this->level
        );
    }

    public function endGame()
    {
        if ($this->player->isAlive()) {
            $this->io->success('Good work ' . $this->player->getName() . '! You have made it alive through the other end');
        } else {
            $this->io->block('Rest in peace ' . $this->player->getName(), null, 'fg=black;bg=green', ' ', true);
        }

        $this->showProgress();
        exit(0);
    }

    /**
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function gameLoop()
    {
        do {
            $this->io->section('Level ' . ($this->level + 1));

            $this->showProgress();

            $doors = $this->generateDoors();

            $doorNames = array_keys($doors);
            $choices   = array_merge($doorNames, $this->actions);

            $choice = $this->io->choice('Carefully choose the door to enter!', $choices);

            // If an action was chosen
            if (in_array($choice, $this->actions)) {
                $this->performAction($choice);
            }

            // If the door had "Walker" in it. Get the player
            // bitten by it and reload the doors.
            if (!empty($doors[$choice])) {
                $walker = $doors[$choice];
                $walker->eat($this->player);
                $this->io->block('Bitten by ' . $walker->getName() . '! Health decreased to ' . $this->player->getHealth(), null, 'fg=white;bg=red', ' ', true);

                continue;
            }

            $this->io->block('Phew! Nothing in that door!', null, 'fg=yellow;bg=blue;', ' ', 1);
            $this->player->addExperience($this->levelDetail['experiencePoints'] ?? 0);

            $this->level++;

        } while ($this->player->isAlive() && $this->loadLevel($this->level));
    }

    protected function initialize()
    {
        $this->showWelcome();

        if ($this->storage->hasSavedGame()) {
            $this->restoreSavedGame();
        }

        $this->loadLevel($this->level ?? 0);

        if (empty($this->player)) {
            $this->choosePlayer();
        }

        $this->io->text('You will be shown some doors!');
        $this->io->text('Carefully choose a door while praying that you do not come across a Walker!');

        $this->io->newLine();
    }

    /**
     * Restores the saved game if available and
     * the choice was made to restore.
     */
    public function restoreSavedGame()
    {
        $restoreGame = $this->io->choice('Saved game found. Would you like to restore it?', ['Yes', 'No']);
        if ($restoreGame === 'No') {
            $this->storage->removeSavedGame();

            return;
        }

        $gameData = $this->storage->getSavedGame();

        $this->player = new $gameData['player']['class'];
        $this->player->setExperience($gameData['player']['experience']);
        $this->player->setHealth($gameData['player']['health']);

        $this->level = $gameData['level'];

        $this->storage->removeSavedGame();
    }

    /**
     * Generate the doors for the current level
     *
     * @return array
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function generateDoors()
    {
        $totalDoors = intval($this->levelDetail['doorCount'] ?? 3);
        $walkers    = $this->levelDetail['walkers'] ?? [];

        // There must be some empty doors
        if (count($walkers) >= $totalDoors) {
            throw new InvalidLevelException('Door count must be greater than walker count');
        }

        // Create allowed number of doors
        $doors = array_fill(0, $totalDoors, false);
        foreach ($walkers as $counter => $walker) {
            $doors[$counter] = new $walker;
        }

        shuffle($doors);

        $namedDoors = [];
        foreach ($doors as $counter => $door) {
            $namedDoors['Door # ' . $counter] = $door;
        }

        return $namedDoors;
    }

    /**
     * Asks for the player choice out of the
     * available players
     */
    public function choosePlayer()
    {
        $players = $this->levelDetail['players'] ?? [];

        $choice = $this->io->choice('Chose your player?', array_keys($players));

        $this->player = new $players[$choice];

        $this->io->title('Godspeed ' . $this->player->getName() . '!');
    }

    /**
     * Advances the game to given level
     *
     * @param int $level
     *
     * @return bool
     */
    public function loadLevel(int $level)
    {
        $map = require __DIR__ . '/../config/map.php';

        // If the next level does not exist
        if (empty($map['levels'][$level])) {
            return false;
        }

        // Set the next level details
        $this->level       = $level;
        $this->levelDetail = $map['levels'][$level];

        return true;
    }

    /**
     * Shows the game title and welcome message
     */
    public function showWelcome()
    {
        $this->io->title('The Walking Dead');
        $this->io->text('Welcome to the world of the dead, see if you can ditch your way through the walkers towards the sanctuary.');
    }

    /**
     * Shows the current progress of the player
     * in tabular form
     */
    public function showProgress()
    {
        $this->io->table(
            ['Level', 'Experience', 'Health'],
            [
                [$this->level + 1, $this->player->getExperience(), $this->player->getHealth()],
            ]
        );
    }
}
