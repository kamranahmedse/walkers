<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class NinjaMichone
 *
 * @package KamranAhmed\Walkers\Walker
 */
class Blind extends BaseWalker
{
    /** @var string */
    protected $name = 'Blind Walker';

    /** @var int */
    protected $damage = 10;

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
