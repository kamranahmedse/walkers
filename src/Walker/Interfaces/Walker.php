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
     * Get the damage that this walker can apply to player
     *
     * @return int
     */
    public function getDamage() : int;

    /**
     * Sets the walker name
     *
     * @param string $name
     */
    public function setName(string $name);

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
