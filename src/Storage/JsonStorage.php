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
    protected $dataFile = 'game-data.json';

    public function initialize()
    {
        if (!is_dir($this->savePath) || !is_writable($this->dataFile)) {
            throw new InvalidStoragePath(sprintf('Storage directory `%s` does not exist or is not readable', $this->savePath));
        }
    }

    public function saveGame(array $player, int $level)
    {
        $gameData = [
            'player' => $player,
            'level'  => $level,
        ];

        $dataFile = rtrim($this->savePath, '/') . '/' . $this->dataFile;

        file_put_contents($dataFile, $this->encryptGameData($gameData));
    }

    protected function encryptGameData(array $gameData) : string
    {
        return json_encode($gameData);
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
