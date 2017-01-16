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
class Map
{
    const SAVE_EXIT = 'Save and Exit';

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

    protected $actions = [
        self::SAVE_EXIT,
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

        do {
            $this->io->section('Level ' . ($this->level + 1));

            $doors = $this->generateDoors();

            $doorNames = array_keys($doors);
            $choices   = array_merge($doorNames, $this->actions);

            $choice = $this->io->choice('Carefully choose the door to enter!', $choices);

            // If an action was chosen
            if (in_array($choice, $this->actions)) {
                $this->performAction($choice);
            }

            // If there was no walker in the door
            if (empty($doors[$choice])) {
                $this->io->block('Well that was close!', null, 'fg=yellow;');
                $this->advanceLevel(++$this->level);
                continue;
            } else {
                $this->io->warning('Woah! You have came across a zombie');
                fgetc(STDIN);
            }

        } while ($this->player->isAlive());
    }

    public function performAction($action)
    {
        switch ($action) {
            case static::SAVE_EXIT:
                $this->saveGame();
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

    protected function initialize()
    {
        if (empty($this->level)) {
            $this->showWelcome();
            $this->advanceLevel(0);
        }

        if (empty($this->player)) {
            $this->choosePlayer();
        }

        $this->io->text('You will be shown some doors! Carefully choose a door while praying that you do not come across a Walker!');
        $this->io->text('Say your prayers and press any key to continue ..');

        fgetc(STDIN);
    }

    public function generateDoors()
    {
        $totalDoors = intval($this->levelDetail['doorCount'] ?? 3);
        $walkers    = $this->levelDetail['walkers'] ?? [];

        if (count($walkers) === $totalDoors) {
            throw new InvalidLevelException('Door count must be less than walker count');
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

    public function choosePlayer()
    {
        // TODO : Empty check
        $players = $this->levelDetail['players'] ?? [];

        $choice = $this->io->choice('Chose your player?', array_keys($players));

        $this->player = new $players[$choice];

        $this->io->title('Godspeed ' . $this->player->getName() . '!');
    }

    public function advanceLevel(int $nextLevel)
    {
        $map = require __DIR__ . '/../config/map.php';

        if (empty($map['levels'][$nextLevel])) {

            // If it was level `0` and we even don't have that
            if (empty($nextLevel)) {
                throw new InvalidLevelException('Specified level does not exist ' . $nextLevel);
            }

            // Any other level and it doesn't exist means end has been reached
            $this->showCompletionExit();
        }

        $this->level       = $nextLevel;
        $this->levelDetail = $map['levels'][$nextLevel];

        if (!empty($this->player)) {
            $this->player->addExperience($this->levelDetail['experiencePoints'] ?? 0);
        }
    }

    public function showWelcome()
    {
        $this->io->title('The Walking Dead');
        $this->io->text('Welcome to the world of the dead, see if you can ditch your way through the walkers towards the sanctuary.');
    }

    public function showCompletionExit()
    {
        $this->io->title('Game Complete');

        $this->io->success('Good work ' . $this->player->getName() . '! You have made it alive through the other end');
        $this->io->listing([
            'Experience: ' . $this->player->getExperience(),
            'Health: ' . $this->player->getHealth(),
        ]);

        exit(0);
    }
}
