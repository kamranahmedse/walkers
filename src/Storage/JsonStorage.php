<?php

namespace KamranAhmed\Walkers\Storage;

use KamranAhmed\Walkers\Exceptions\InvalidStoragePath;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;

/**
 * Class JsonStorage
 *
 * @package KamranAhmed\Walkers\Storage
 */
class JsonStorage implements GameStorage
{
    protected $savePath = __DIR__ . '/../../storage';
    protected $dataFile = 'game-data.wd';

    /**
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidStoragePath
     */
    public function initialize()
    {
        if (!is_dir($this->savePath) || !is_writable($this->dataFile)) {
            throw new InvalidStoragePath(sprintf('Storage directory `%s` does not exist or is not readable', $this->savePath));
        }
    }

    /**
     * @param array $player
     * @param int   $level
     */
    public function saveGame(array $player, int $level)
    {
        $gameData = [
            'player' => $player,
            'level'  => $level,
        ];

        $dataFile = rtrim($this->savePath, '/') . '/' . $this->dataFile;

        file_put_contents($dataFile, $this->encryptGameData($gameData));
    }

    /**
     * @param array $gameData
     *
     * @return string
     */
    protected function encryptGameData(array $gameData) : string
    {
        $gameData = json_encode($gameData);

        return str_rot13(base64_encode(str_rot13($gameData)));
    }

    public function restoreGame()
    {
        // TODO: Implement restoreGame() method.
    }

    public function hasSavedGame() : bool
    {
        // TODO: Implement hasSavedGame() method.
    }
}
