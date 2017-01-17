<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class Grouchy
 *
 * @package KamranAhmed\Walkers\Walker
 */
class Grouchy extends BaseWalker
{
    /** @var string */
    protected $name = 'Grouchy Walker';

    /** @var int */
    protected $damage = 30;

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
