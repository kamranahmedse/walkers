<?php

namespace KamranAhmed\Walkers\Storage\Interfaces;

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
     * @param array $player
     * @param int   $level
     *
     * @return void
     */
    public function saveGame(array $player, int $level);

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
