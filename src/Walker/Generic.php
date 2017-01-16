<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class Generic
 *
 * @package KamranAhmed\Walkers\Walker
 */
class Generic extends BaseWalker
{
    /** @var string */
    protected $name = 'Common Walker';

    /** @var int */
    protected $damage = 5;

    public function eat(Player $player)
    {
        $player->setHealth($player->getHealth() - $this->damage);
    }
}
