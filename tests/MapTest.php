<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Walkers\Map;
use PHPUnit_Framework_TestCase;

/**
 * Class MapTest
 *
 * @package KamranAhmed\Tests
 */
class MapTest extends PHPUnit_Framework_TestCase
{
    protected $storagePath = __DIR__ . '/fixtures';

    /**
     * @expectedException  \KamranAhmed\Walkers\Exceptions\InvalidMapFile
     */
    public function testInvalidMapFileThrowsException()
    {
        $this->getMap('map-invalid.php');
    }

    /**
     * @expectedException  \KamranAhmed\Walkers\Exceptions\InvalidMapFile
     */
    public function testNonExistingMapThrowsException()
    {
        $this->getMap('map-non-existing.php');
    }

    public function testCanLoadValidMap()
    {
        $map = $this->getMap('map-3-level.php');

        $this->assertEquals(3, $map->getLevelCount());
    }

    public function testCanLoadSpecificLevel()
    {
        $map = $this->getMap('map-3-level.php');
        $map->loadLevel(1);

        $this->assertEquals(1, $map->getCurrentLevel());
        $this->assertEquals(20, $map->getCurrentLevelExperience());
        $this->assertEquals(2, $map->getWalkerCount());
        $this->assertEquals(5, $map->getDoorCount());
        $this->assertNotEmpty($map->getPlayers());
        $this->assertNotEmpty($map->getWalkers());
    }

    /**
     * @dataProvider verifyLevelDataProvider
     *
     * @param string $mapFile
     * @param array  $existingLevels
     * @param array  $nonExistingLevels
     */
    public function testCanVerifyIfLevelExists(string $mapFile, array $existingLevels, array $nonExistingLevels)
    {
        $map = $this->getMap($mapFile);

        array_map(function ($level) use ($map) {
            $this->assertTrue($map->hasLevel($level));
        }, $existingLevels);

        array_map(function ($level) use ($map) {
            $this->assertFalse($map->hasLevel($level));
        }, $nonExistingLevels);
    }

    public function testCanAdvanceIfPossible()
    {
        $map = $this->getMap('map-3-level.php');

        $map->loadLevel(0);

        // Check if can Advance
        $this->assertTrue($map->canAdvance());

        // Advance to next level and see if it is advanced
        $map->advance();

        $this->assertEquals(1, $map->getCurrentLevel());
        $this->assertEquals(20, $map->getCurrentLevelExperience());
        $this->assertEquals(2, $map->getWalkerCount());
        $this->assertEquals(5, $map->getDoorCount());
        $this->assertNotEmpty($map->getPlayers());
        $this->assertNotEmpty($map->getWalkers());

        $map->advance();
        $this->assertEquals(2, $map->getCurrentLevel());
    }

    public function testCanGetLevelDoors()
    {
        $map = $this->getMap('map-3-level.php');

        // TODO
    }

    public function verifyLevelDataProvider()
    {
        return [
            ['map-3-level.php', [0, 1, 2], [3, 4]],
            ['map-2-level.php', [0, 1], [2, 3, 4]],
        ];
    }

    /**
     * @param $fileName
     *
     * @return \KamranAhmed\Walkers\Map
     */
    public
    function getMap($fileName) : Map
    {
        return new Map($this->storagePath . '/' . $fileName);
    }
}
