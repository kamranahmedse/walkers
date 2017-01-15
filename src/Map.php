<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Exceptions\InvalidLevelException;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Map
 *
 * @package KamranAhmed\Walkers
 */
class Map
{
    /** @var int */
    protected $level;

    /** @var SymfonyStyle */
    protected $io;

    /** @var Player */
    protected $player;

    /** @var array */
    protected $levelDetail;

    /**
     * Map constructor.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     * @param int                                           $level
     */
    public function __construct(SymfonyStyle $io, Player $player = null, $level = 0)
    {
        $this->io     = $io;
        $this->player = $player;

        $this->populateLevel($level);
    }

    public function play()
    {
        $this->initialize();
    }

    protected function initialize()
    {
        if (empty($this->level)) {
            $this->showWelcome();
        }

        if (empty($this->player)) {
            $this->choosePlayer();
        }
    }

    public function choosePlayer()
    {
        // TODO : Empty check
        $players = $this->levelDetail['players'] ?? [];

        $choice = $this->io->choice('Chose your player?', array_keys($players));

        $this->player = new $players[$choice];

        $this->io->title('Godspeed ' . $this->player->getName() . '!');
    }

    public function populateLevel($level)
    {
        $map = require __DIR__ . '/../config/map.php';
        if (empty($map['levels'][$level])) {
            throw new InvalidLevelException('Invalid level number ' . $level);
        }

        $this->level       = $level;
        $this->levelDetail = $map['levels'][$level];
    }

    public function showWelcome()
    {
        $this->io->title('The Walking Dead');
        $this->io->text('Welcome to the world of the dead, see if you can ditch your way through the walkers towards the sanctuary.');
    }
}
