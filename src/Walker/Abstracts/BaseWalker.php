<?php

namespace KamranAhmed\Walkers\Walker\Abstracts;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Interfaces\Walker;

/**
 * Class BaseWalker
 *
 * @package KamranAhmed\Walkers\Walker\Abstracts
 */
abstract class BaseWalker implements Walker
{
    /** @var string */
    protected $name = 'Unknown';

    /** @var int */
    protected $damage = 0;

    /**
     * Gets the damage that walker can apply to player
     *
     * @return int
     */
    public function getDamage() : int
    {
        return $this->damage;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     *
     * @return void
     */
    public function eat(Player $player)
    {
        $player->setHealth($player->getHealth() - $this->damage);
    }
}
