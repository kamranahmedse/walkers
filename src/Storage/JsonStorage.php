<?php

namespace KamranAhmed\Walkers\Storage;

use KamranAhmed\Walkers\Exceptions\InvalidGameData;
use KamranAhmed\Walkers\Exceptions\InvalidStoragePath;
use KamranAhmed\Walkers\Exceptions\NoSavedGame;
use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;

/**
 * Class JsonStorage
 *
 * @package KamranAhmed\Walkers\Storage
 */
class JsonStorage implements GameStorage
{
    /** @var string */
    protected $savePath;

    /** @var string */
    protected $dataFile;

    /**
     * JsonStorage constructor.
     *
     * @param        $saveDir
     * @param string $dataFile
     */
    public function __construct($saveDir, $dataFile = 'game-data.wd')
    {
        $this->savePath = $saveDir;
        $this->dataFile = $dataFile;
    }

    /**
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidStoragePath
     * @return void
     */
    public function initialize()
    {
        if (!is_dir($this->savePath) || !is_writable($this->savePath)) {
            throw new InvalidStoragePath(sprintf('Storage directory `%s` does not exist or is not readable', $this->savePath));
        }
    }

    /**
     * Gets the file where game data is to be stored
     *
     * @return string
     */
    protected function getDataFile() : string
    {
        return rtrim($this->savePath, '/') . '/' . $this->dataFile;
    }

    /**
     * Removes the saved game
     *
     * @return void
     */
    public function removeSavedGame()
    {
        if ($this->hasSavedGame()) {
            unlink($this->getDataFile());
        }

        return;
    }

    /**
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     * @param \KamranAhmed\Walkers\Map                      $map
     *
     * @return void
     */
    public function saveGame(Player $player, Map $map)
    {
        $gameData = [
            'player' => $player->toArray(),
            'level'  => $map->getCurrentLevel(),
        ];

        $dataFile = $this->getDataFile();

        file_put_contents($dataFile, $this->encodeGameData($gameData));
    }

    /**
     * @param array $gameData
     *
     * @return string
     */
    protected function encodeGameData(array $gameData) : string
    {
        $gameData = json_encode($gameData);

        return str_rot13(base64_encode(str_rot13($gameData)));
    }

    /**
     * @param string $gameData
     *
     * @return array
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidGameData
     */
    protected function decodeGameData(string $gameData) : array
    {
        $jsonData = str_rot13(base64_decode(str_rot13($gameData)));
        $gameData = json_decode($jsonData, true);

        if (
            empty($gameData) ||
            empty($gameData['player']) ||
            !is_int($gameData['level'])
        ) {
            throw new InvalidGameData('Invalid game data found');
        }

        return $gameData;
    }

    /**
     * @return array
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidGameData
     * @throws \KamranAhmed\Walkers\Exceptions\NoSavedGame
     */
    public function getSavedGame() : array
    {
        if (!$this->hasSavedGame()) {
            throw new NoSavedGame('No saved game found');
        }

        $gameData = file_get_contents($this->getDataFile());

        return $this->decodeGameData($gameData);
    }

    /**
     * @return bool
     */
    public function hasSavedGame() : bool
    {
        $dataFile = $this->getDataFile();

        return file_exists($dataFile) && is_readable($dataFile);
    }
}
