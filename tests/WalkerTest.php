<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Walker\Blind;
use KamranAhmed\Walkers\Walker\Deadly;
use KamranAhmed\Walkers\Walker\Grouchy;
use KamranAhmed\Walkers\Walker\Interfaces\Walker;
use KamranAhmed\Walkers\Walker\OneLegged;
use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * Class WalkerTest
 *
 * @package KamranAhmed\Tests
 */
class WalkerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider walkersProvider
     *
     * @param \KamranAhmed\Walkers\Walker\Interfaces\Walker $walker
     */
    public function testCanGetWalkerName(Walker $walker)
    {
        // Yeah, I know I could use `Faker` but its fine for now
        $name = time();

        $walker->setName($name);
        $this->assertEquals($name, $walker->getName());
    }

    /**
     * @dataProvider walkersProvider
     *
     * @param \KamranAhmed\Walkers\Walker\Interfaces\Walker $walker
     */
    public function testCanEatPlayer(Walker $walker)
    {
        $player    = new GunnerRick();
        $oldHealth = $player->getHealth();

        $this->assertTrue($player->isAlive());
        $walker->eat($player);

        $newHealth = $player->getHealth();
        $this->assertNotEquals($oldHealth, $newHealth);
        $this->assertEquals($newHealth, $oldHealth - $walker->getDamage());

        // Test can eat player to death
        while ($player->isAlive()) {
            $walker->eat($player);
        }

        $this->assertFalse($player->isAlive());
    }

    public function walkersProvider()
    {
        return [
            [new Blind()],
            [new Deadly()],
            [new Grouchy()],
            [new OneLegged()],
        ];
    }
}
