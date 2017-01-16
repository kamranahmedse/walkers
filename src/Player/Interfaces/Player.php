<?php

namespace KamranAhmed\Walkers\Player\Interfaces;

/**
 * Interface Player
 */
interface Player
{
    public function getHealth();

    public function getExperience();

    public function getName();

    public function isAlive() : bool;

    public function addExperience(int $points = 10);

    public function toArray() : array;
}
