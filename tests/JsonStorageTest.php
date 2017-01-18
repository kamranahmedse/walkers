<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Storage\JsonStorage;
use PHPUnit_Framework_TestCase;

/**
 * Class JsonStorageTest
 *
 * @package KamranAhmed\Tests
 */
class JsonStorageTest extends PHPUnit_Framework_TestCase
{
    protected $fixturesPath     = __DIR__ . '/fixtures';
    protected $dataFile         = 'test-game-data.wd';
    protected $fullGameDataPath = __DIR__ . '/fixtures/test-game-data.wd';
    protected $fullMapFilePath  = __DIR__ . '/fixtures/map.php';

    public function setUp()
    {
        if (file_exists($this->fullGameDataPath)) {
            unlink($this->fullGameDataPath);
        }
    }

    /**
     * Checks if the encrypted game data is saved and not
     * just JSON is dumped
     */
    public function testCanSaveGame()
    {
        // Simulate a player
        $player = new GunnerRick();
        $player->setHealth(113);

        // Start from the first level `0` and advance to `1`
        $map = new Map($this->fullMapFilePath);
        $map->advance();

        // Save game
        $storage = new JsonStorage($this->fixturesPath, $this->dataFile);
        $storage->saveGame($player, $map);

        $this->assertFileExists($this->fullGameDataPath);

        $this->assertTrue($storage->hasSavedGame());

        // Is not a valid JSON
        $savedContent = file_get_contents($this->fullGameDataPath);
        $this->assertNull(json_decode($savedContent, true));
    }

    public function testCanGetSavedGame()
    {
        // Simulate a player
        $player = new GunnerRick();
        $player->setHealth(13);
        $player->addExperience(122);

        // Start from the first level `0` and advance to `1`
        $map = new Map($this->fullMapFilePath);
        $map->advance();

        // Save game
        $storage = new JsonStorage($this->fixturesPath, $this->dataFile);
        $storage->saveGame($player, $map);

        $savedGame = $storage->getSavedGame();

        $this->assertEquals(13, $savedGame['player']['health']);
        $this->assertEquals(122, $savedGame['player']['experience']);
        $this->assertEquals(get_class($player), $savedGame['player']['class']);

        $this->assertEquals(1, $map->getCurrentLevel());
    }

    /**
     * @expectedException \KamranAhmed\Walkers\Exceptions\NoSavedGame
     */
    public function testCantGetNonSavedGame()
    {
        $storage = new JsonStorage($this->fixturesPath, $this->dataFile);
        $storage->getSavedGame();
    }

    public function testCanRemoveSavedGame()
    {
        // Simulate a player
        $player = new GunnerRick();
        $player->setHealth(3);
        $player->addExperience(12);

        // Start from the first level `0` and advance to `1`
        $map = new Map($this->fullMapFilePath);
        $map->advance();

        // Save game
        $storage = new JsonStorage($this->fixturesPath, $this->dataFile);
        $storage->saveGame($player, $map);

        $this->assertTrue($storage->hasSavedGame());
        $storage->removeSavedGame();
        $this->assertFalse($storage->hasSavedGame());
    }
}
