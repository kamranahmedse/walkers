<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Tests\Fakes\GameDouble;
use KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface;
use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Storage\JsonStorage;
use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * Class GameTest
 *
 * @package KamranAhmed\Tests
 */
class GameTest extends PHPUnit_Framework_TestCase
{
    protected $storagePath = __DIR__ . '/fixtures';
    protected $dataFile    = 'some-file.wd';

    public function testUserCanChooseAndRestoreGame()
    {
        $console = $this->getEmptyConsoleMock(['askChoice']);

        // Assert that the user is asked to restore the game
        $console->shouldReceive('askChoice')
                ->once()
                ->withArgs(['Saved game found. Would you like to restore it?', ['Yes', 'No']])
                ->andReturn('Yes');

        // Assert that the user is asked to restore the game
        $console->shouldReceive('askChoice')
                ->never()
                ->withArgs(['Chose your player?', Mockery::any()])
                ->andReturn(null);

        $map = new Map($this->storagePath . '/map-3-level.php');

        // Storage mock
        $storage = Mockery::mock(JsonStorage::class);
        $storage->shouldReceive('hasSavedGame')->once()->andReturn(true);
        $storage->shouldReceive('removeSavedGame')->once()->andReturn(true);
        $storage->shouldReceive('getSavedGame')
                ->once()
                ->andReturn([
                    'player' => [
                        'class'      => KidCarl::class,
                        'experience' => 40,
                        'health'     => 20,
                    ],
                    'level'  => 1,
                ]);

        $game = new GameDouble($console, $storage, $map);
        $game->initialize();

        $this->assertInstanceOf(KidCarl::class, $game->player);
        $this->assertEquals(40, $game->player->getExperience());
        $this->assertEquals(20, $game->player->getHealth());
        $this->assertEquals(1, $game->map->getCurrentLevel());
    }

    public function testUserCanChooseAndNotRestoreGame()
    {
        $console = $this->getEmptyConsoleMock(['askChoice']);

        // Assert that the user is asked to restore the game
        $console->shouldReceive('askChoice')
                ->once()
                ->withArgs(['Saved game found. Would you like to restore it?', ['Yes', 'No']])
                ->andReturn('No');

        // Assert that the user is asked to restore the game
        $console->shouldReceive('askChoice')
                ->once()
                ->withArgs(['Chose your player?', Mockery::any()])
                ->andReturn('Rick - The Father');

        $map = new Map($this->storagePath . '/map-3-level.php');

        // Storage mock
        $storage = Mockery::mock(JsonStorage::class);
        $storage->shouldReceive('hasSavedGame')->once()->andReturn(true);
        $storage->shouldReceive('removeSavedGame')->once()->andReturn(true);

        $game = new GameDouble($console, $storage, $map);
        $game->initialize();

        $this->assertInstanceOf(GunnerRick::class, $game->player);
        $this->assertEquals(0, $game->player->getExperience());
        $this->assertEquals(100, $game->player->getHealth());
        $this->assertEquals(0, $game->map->getCurrentLevel());
    }

    public function getEmptyConsoleMock($mockExcept = [])
    {
        $console = Mockery::mock(ConsoleInterface::class);

        $methods = [
            'askChoice'    => [Mockery::any(), Mockery::any()],
            'askQuestion'  => [Mockery::any(), Mockery::any()],
            'showSection'  => [Mockery::any()],
            'printDanger'  => [Mockery::any()],
            'printSuccess' => [Mockery::any()],
            'printInfo'    => [Mockery::any()],
            'printText'    => [Mockery::any()],
            'printTitle'   => [Mockery::any()],
            'breakLine'    => [],
            'printTable'   => [Mockery::any(), Mockery::any()],
        ];


        foreach ($methods as $method => $args) {
            if (in_array($method, $mockExcept)) {
                continue;
            }

            $console->shouldReceive($method)->withArgs($args)->zeroOrMoreTimes()->andReturn(null);
        }

        return $console;
    }

    // TODO : Automate this using `phpunit.xml`
    public function tearDown()
    {
        Mockery::close();
    }
}
