<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Tests\Fakes\GameDouble;
use KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface;
use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Storage\JsonStorage;
use KamranAhmed\Walkers\Walker\Blind;
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
                ->withArgs(['Choose your player?', Mockery::any()])
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
                ->withArgs(['Choose your player?', Mockery::any()])
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

    /**
     * @dataProvider threeLeveledMapPlayersProvider
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     * @param                                               $choice
     */
    public function testUserCanChoosePlayer(Player $player, $choice)
    {
        $storage = Mockery::mock(JsonStorage::class);
        $map     = new Map($this->storagePath . '/map-3-level.php');
        $console = $this->getEmptyConsoleMock(['printTitle', 'askChoice']);

        $console->shouldReceive('printTitle')
                ->once()
                ->with('Godspeed ' . $player->getName() . '!')
                ->andReturn();

        $console->shouldReceive('askChoice')
                ->once()
                ->with('Choose your player?', Mockery::any())
                ->andReturn($choice);

        $game = new GameDouble($console, $storage, $map);
        $game->choosePlayer();
        $playerChoice = $game->player;

        $this->assertInstanceOf(get_class($player), $playerChoice);
    }

    public function threeLeveledMapPlayersProvider()
    {
        return [
            [new GunnerRick(), 'Rick - The Father'],
            [new KidCarl(), 'Carl - The Kid'],
        ];
    }

    public function testWalkerDoorCanBeIdentified()
    {
        $console = $this->getEmptyConsoleMock();
        $map     = new Map($this->storagePath . '/map-3-level.php');
        $storage = Mockery::mock(JsonStorage::class);

        $game = new GameDouble($console, $storage, $map);

        $doors = [
            'Door # 1' => false,
            'Door # 2' => new Blind(),
            'Door # 3' => false,
        ];

        $this->assertTrue($game->isWalkerDoor($doors, 'Door # 2'));
        $this->assertFalse($game->isWalkerDoor($doors, 'Door # 1'));
    }

    public function testEndGameShowsTheStatsIfPlayerIsAlive()
    {
        $storage = Mockery::mock(JsonStorage::class);
        $player  = new GunnerRick();
        $map     = new Map($this->storagePath . '/map-3-level.php');
        $console = $this->getEmptyConsoleMock(['printSuccess', 'printTable']);

        $console->shouldReceive('printTable')
                ->once()
                ->with(['Level', 'Experience', 'Health'], Mockery::any());

        $console->shouldReceive('printSuccess')
                ->once()
                ->with('Good work ' . $player->getName() . '! You have made it alive to the Sanctuary')
                ->andReturn();


        $game         = new GameDouble($console, $storage, $map);
        $game->player = $player;

        $game->endGame();
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
