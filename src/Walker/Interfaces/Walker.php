<?php

namespace KamranAhmed\Walkers\Walker\Interfaces;

use KamranAhmed\Walkers\Player\Interfaces\Player;

/**
 * Interface Walker
 */
interface Walker
{
    public function getHealth();

    public function eat(Player $player);
}
