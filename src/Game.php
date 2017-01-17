<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface;
use KamranAhmed\Walkers\Exceptions\InvalidLevelException;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;

/**
 * Class Map
 *
 * @package KamranAhmed\Walkers
 */
class Game
{
    /** @var int */
    protected $level;

    /** @var ConsoleInterface */
    protected $console;

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
     * @param \KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface $console
     * @param \KamranAhmed\Walkers\Storage\Interfaces\GameStorage      $storage
     *
     */
    public function __construct(ConsoleInterface $console, GameStorage $storage)
    {
        $this->console = $console;
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
                $this->console->printSuccess('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
                exit(0);
            case static::EXIT:
                $this->console->printSuccess('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
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
            $this->console->printSuccess('Good work ' . $this->player->getName() . '! You have made it alive through the other end');
        } else {
            $this->console->printDanger('Rest in peace ' . $this->player->getName());
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
            $this->console->printTitle('Level ' . ($this->level + 1));

            $this->showProgress();

            $doors = $this->generateDoors();

            $doorNames = array_keys($doors);
            $choices   = array_merge($doorNames, $this->actions);

            $choice = $this->console->askChoice('Carefully choose the door to enter!', $choices);

            // If an action was chosen
            if (in_array($choice, $this->actions)) {
                $this->performAction($choice);
            }

            // If the door had "Walker" in it. Get the player
            // bitten by it and reload the doors.
            if (!empty($doors[$choice])) {
                $walker = $doors[$choice];
                $walker->eat($this->player);

                $this->console->printDanger('Bitten by ' . $walker->getName() . '! Health decreased to ' . $this->player->getHealth());

                continue;
            }

            $this->console->printInfo('Phew! Nothing in that door!');
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

        $this->console->print('You will be shown some doors!');
        $this->console->print('Carefully choose a door while praying that you do not come across a Walker!');

        $this->console->breakLine();
    }

    /**
     * Restores the saved game if available and
     * the choice was made to restore.
     */
    public function restoreSavedGame()
    {
        $restoreGame = $this->console->askChoice('Saved game found. Would you like to restore it?', ['Yes', 'No']);
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

        $choice = $this->console->askChoice('Chose your player?', array_keys($players));

        $this->player = new $players[$choice];

        $this->console->printTitle('Godspeed ' . $this->player->getName() . '!');
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
        $this->console->printTitle('The Walking Dead');
        $this->console->print('Welcome to the world of the dead, see if you can ditch your way through the walkers towards the sanctuary.');
    }

    /**
     * Shows the current progress of the player
     * in tabular form
     */
    public function showProgress()
    {
        $this->console->printTable(
            ['Level', 'Experience', 'Health'],
            [
                [$this->level + 1, $this->player->getExperience(), $this->player->getHealth()],
            ]
        );
    }
}
