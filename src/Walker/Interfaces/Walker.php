<?php

namespace KamranAhmed\Walkers\Walker\Interfaces;

use KamranAhmed\Walkers\Player\Interfaces\Player;

/**
 * Interface Walker
 */
interface Walker
{
    /**
     * Gets the name for walker
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Grabs the bite out of player. Each walker's bite can have
     * a different affect on the player.
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     *
     * @return void
     */
    public function eat(Player $player);
}
