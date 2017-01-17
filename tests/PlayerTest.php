<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Player\NinjaMichone;
use KamranAhmed\Walkers\Player\OldHershel;
use KamranAhmed\Walkers\Player\RunnerGlenn;
use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * Class PlayerTest
 *
 * @package KamranAhmed\Tests
 */
class PlayerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider playersProvider
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     */
    public function testCanAccessHealth(Player $player)
    {
        // Because each of the players has some default health
        $this->assertTrue($player->isAlive());

        $player->setHealth(23);
        $this->assertEquals(23, $player->getHealth());
        
        $this->assertTrue($player->isAlive());
    }

    /**
     * @dataProvider playersProvider
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     */
    public function testCanAddExperience(Player $player)
    {
        $player->addExperience(10);
        $this->assertEquals($player->getExperience(), 10);

        $player->addExperience(13);
        $this->assertEquals($player->getExperience(), 23);
    }

    /**
     * @dataProvider playersProvider
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     */
    public function testCanGetPlayerName(Player $player)
    {
        // Yeah, I know I could use `Faker`, but its fine for now
        $name = time();

        $player->setName($name);
        $this->assertEquals($name, $player->getName());
    }

    public function playersProvider()
    {
        return [
            [new GunnerRick()],
            [new KidCarl()],
            [new NinjaMichone()],
            [new OldHershel()],
            [new RunnerGlenn()],
        ];
    }
}
