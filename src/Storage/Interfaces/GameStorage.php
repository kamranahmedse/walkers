<?php

namespace KamranAhmed\Walkers\Storage\Interfaces;

/**
 * Interface GameStorage
 *
 * @package KamranAhmed\Walkers\Storage\Interfaces
 */
interface GameStorage
{
    public function initialize();

    public function saveGame(array $player, int $level);

    public function restoreGame();

    public function hasSavedGame() : bool;
}
