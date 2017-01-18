<?php

namespace KamranAhmed\Walkers\Storage\Interfaces;

use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\Interfaces\Player;

/**
 * Interface GameStorage
 *
 * @package KamranAhmed\Walkers\Storage\Interfaces
 */
interface GameStorage
{
    /**
     * Any setup e.g. db connection initialization etc
     *
     * @return void
     */
    public function initialize();

    /**
     * Saves the current progress of user
     *
     * @param \KamranAhmed\Walkers\Player\Interfaces\Player $player
     * @param \KamranAhmed\Walkers\Map                      $map
     *
     * @return void
     */
    public function saveGame(Player $player, Map $map);

    /**
     * Gets the saved game for the restore
     *
     * @return array
     */
    public function getSavedGame() : array;

    /**
     * Removes the saved game from storage
     *
     * @return void
     */
    public function removeSavedGame();

    /**
     * Checks if the user has saved game or not
     *
     * @return bool
     */
    public function hasSavedGame() : bool;
}
