<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class Deadly
 *
 * @package KamranAhmed\Walkers\Walker
 */
class Deadly extends BaseWalker
{
    /** @var string */
    protected $name = 'Deadly Walker';

    /** @var int */
    protected $damage = 25;

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
