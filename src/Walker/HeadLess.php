<?php

namespace KamranAhmed\Walkers\Walker;

use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Walker\Abstracts\BaseWalker;

/**
 * Class Harmless
 *
 * @package KamranAhmed\Walkers\Walker
 */
class HeadLess extends BaseWalker
{
    /** @var string */
    protected $name = 'Headless Walker';

    /** @var int */
    protected $damage = 0;

    public function eat(Player $player)
    {
        $player->setHealth($player->getHealth() - $this->damage);
    }
}
