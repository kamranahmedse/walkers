<?php

namespace KamranAhmed\Walkers\Walker\Interfaces;

use KamranAhmed\Walkers\Player\Interfaces\Player;

/**
 * Interface Walker
 */
interface Walker
{
    public function getName();

    public function eat(Player $player);
}
