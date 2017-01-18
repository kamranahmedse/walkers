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

    /**
     * @expectedException  \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function testThrowsExceptionWhenLoadingInvalidLevel()
    {
        $map = $this->getMap('map-3-level.php');

        $map->loadLevel(5);
    }

    public function testFirstLevelIsLoadedByDefault()
    {
        $map = $this->getMap('map-3-level.php');

        $this->assertEquals(0, $map->getCurrentLevel());
        $this->assertEquals(10, $map->getCurrentLevelExperience());
        $this->assertEquals(1, $map->getWalkerCount());
        $this->assertEquals(3, $map->getDoorCount());
        $this->assertNotEmpty($map->getPlayers());
        $this->assertNotEmpty($map->getWalkers());
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

        // Get the doors in first level
        $doors = $map->getDoors();
        $this->assertCount(3, $doors);

        // Advance to next level
        $map->advance();

        // Get the doors in next level
        $doors = $map->getDoors();
        $this->assertCount(5, $doors);
    }

    public function testLevelDoorsAreNamed()
    {
        $map = $this->getMap('map-3-level.php');

        // Get the doors in first level
        $doors = $map->getDoors();
        $this->assertCount(3, $doors);

        // Check if all the doors returned are named
        $foundDoors = array_keys($doors);
        $namedDoors = array_filter($foundDoors, function ($doorName) {
            return is_string($doorName) && stripos($doorName, 'door') !== false;
        });

        $this->assertEquals(count($foundDoors), count($namedDoors));
    }

    public function testCanShuffleDoors()
    {
        $map = $this->getMap('map-3-level.php');

        // Advance to next level i.e. `level 1`
        $map->advance();

        // Get the doors in next level
        $iter1Doors = $map->getDoors(true);
        $iter2Doors = $map->getDoors(true);
        $iter3Doors = $map->getDoors(true);

        $iter1Walkers = array_filter($iter1Doors, 'is_object');
        $iter1Walkers = implode(',', array_keys($iter1Walkers));

        $iter2Walkers = array_filter($iter2Doors, 'is_object');
        $iter2Walkers = implode(',', array_keys($iter2Walkers));

        $iter3Walkers = array_filter($iter3Doors, 'is_object');
        $iter3Walkers = implode(',', array_keys($iter3Walkers));

        $this->assertTrue($iter1Walkers !== $iter2Walkers || $iter1Walkers !== $iter3Walkers);
    }

    public function testCanGetUnShuffleDoors()
    {
        $map = $this->getMap('map-3-level.php');

        // Advance to next level i.e. `level 1`
        $map->advance();

        // Get the doors in next level
        $iter1Doors = $map->getDoors();
        $iter2Doors = $map->getDoors();
        $iter3Doors = $map->getDoors();

        $iter1Walkers = array_filter($iter1Doors, 'is_object');
        $iter1Walkers = implode(',', array_keys($iter1Walkers));

        $iter2Walkers = array_filter($iter2Doors, 'is_object');
        $iter2Walkers = implode(',', array_keys($iter2Walkers));

        $iter3Walkers = array_filter($iter3Doors, 'is_object');
        $iter3Walkers = implode(',', array_keys($iter3Walkers));

        $this->assertTrue($iter1Walkers === $iter2Walkers || $iter1Walkers === $iter3Walkers);
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
    public function getMap($fileName) : Map
    {
        return new Map($this->storagePath . '/' . $fileName);
    }
}
