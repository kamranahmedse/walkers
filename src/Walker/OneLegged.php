<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class OneLegged
 *
 * @package KamranAhmed\Walkers\Walker
 */
class OneLegged extends BaseWalker
{
    /** @var string */
    protected $name = 'One Legged Walker';

    /** @var int */
    protected $damage = 5;

    public function eat(Player $player)
    {
        $player->setHealth($player->getHealth() - $this->damage);
    }
}
