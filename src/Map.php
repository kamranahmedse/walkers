<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Exceptions\InvalidLevelException;
use KamranAhmed\Walkers\Exceptions\InvalidMapFile;

/**
 * Class Generic
 *
 * @package KamranAhmed\Walkers\Map
 */
class Map
{
    /** @var string */
    protected $mapPath;

    /** @var  array */
    protected $mapDetail;

    /** @var int */
    protected $level;

    /** @var array */
    protected $levelDetail;

    /** @var array */
    protected $doors;

    /**
     * Generic constructor.
     *
     * @param string $mapPath
     */
    public function __construct(string $mapPath)
    {
        $this->validateMap($mapPath);
        $this->loadMap($mapPath);
    }

    /**
     * @param $mapPath
     *
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidMapFile
     */
    public function validateMap($mapPath)
    {
        if (!is_file($mapPath) || !is_readable($mapPath)) {
            throw new InvalidMapFile('Map file does not exist or is not readable');
        }

        $mapDetail = require $mapPath;
        if (!is_array($mapDetail) || empty($mapDetail['levels'])) {
            throw new InvalidMapFile('Invalid map file given');
        }
    }

    /**
     * @param $mapPath
     *
     * @return void
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidMapFile
     */
    protected function loadMap($mapPath)
    {
        $this->level     = 0;
        $this->mapPath   = $mapPath;
        $this->mapDetail = require $mapPath;
        $this->doors     = $this->getDoors();

        $this->loadLevel($this->level);
    }

    /**
     * Loads the specified level
     *
     * @param int $level
     *
     * @return void
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function loadLevel(int $level)
    {
        // If the passed level does not exist
        if (!$this->hasLevel($level)) {
            throw new InvalidLevelException('Level not found');
        }

        $this->level       = $level;
        $this->levelDetail = $this->mapDetail['levels'][$level];

        // Check if the loaded level is valid
        $this->validateLevel();

        // Generate the doors if valid
        $this->doors = $this->generateDoors();
    }

    /**
     * @param int $level
     *
     * @return bool
     */
    public function hasLevel(int $level)
    {
        return !empty($this->mapDetail['levels'][$level]);
    }

    /**
     * Can advance to next level or not
     *
     * @return bool
     */
    public function canAdvance()
    {
        return $this->hasLevel($this->level + 1);
    }

    /**
     * Advance to next level if possible
     *
     * @return bool
     */
    public function advance()
    {
        if (!$this->canAdvance()) {
            return false;
        }

        $this->loadLevel($this->level + 1);

        return true;
    }

    /**
     * Gets the doors for current level
     *
     * @param bool $reShuffle
     *
     * @return array
     */
    public function getDoors($reShuffle = false)
    {
        if (!$reShuffle) {
            return $this->doors;
        }

        $doors = array_values($this->doors);
        shuffle($doors);

        return $this->nameDoors($doors);
    }

    /**
     * @return array
     */
    public function getPlayers()
    {
        return $this->levelDetail['players'] ?? [];
    }

    /**
     * @return int
     */
    public function getCurrentLevel()
    {
        return $this->level;
    }

    /**
     * Generates doors for current level
     *
     * @return array
     */
    protected function generateDoors()
    {
        $doorCount = $this->getDoorCount();

        // Create required number of empty doors
        $doors = array_fill(0, $doorCount, false);

        // Put each of the walkers in a door
        $walkers = $this->getWalkers();
        foreach ($walkers as $counter => $walker) {
            $doors[$counter] = new $walker;
        }

        // Shuffle the doors to randomize the placement
        shuffle($doors);

        return $this->nameDoors($doors);
    }

    /**
     * Names the doors
     *
     * @param array $doors
     *
     * @return array
     */
    protected function nameDoors(array $doors)
    {
        $namedDoors = [];

        // Generate named doors
        foreach ($doors as $counter => $door) {
            $namedDoors['Door # ' . $counter] = $door;
        }

        return $namedDoors;
    }

    /**
     * Validates if the level has valid number of doors and walkers
     *
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    protected function validateLevel()
    {
        $doorCount   = $this->getDoorCount();
        $walkerCount = $this->getWalkerCount();

        // There must be some empty doors
        if ($walkerCount >= $doorCount) {
            throw new InvalidLevelException('Door count must be greater than walker count');
        }
    }

    /**
     * Gets the total number of levels in this map
     *
     * @return int
     */
    public function getLevelCount()
    {
        return count($this->mapDetail['levels']);
    }

    /**
     * Gets the door count in current level
     *
     * @return int
     */
    public function getDoorCount()
    {
        return intval($this->levelDetail['doorCount'] ?? 3);
    }

    /**
     * Gets the walker count in current level
     *
     * @return int
     */
    public function getWalkerCount()
    {
        return count($this->levelDetail['walkers'] ?? []);
    }

    /**
     * @return array
     */
    public function getWalkers()
    {
        return $this->levelDetail['walkers'] ?? [];
    }

    /**
     * @return int
     */
    public function getCurrentLevelExperience()
    {
        return $this->levelDetail['experiencePoints'] ?? 0;
    }
}
