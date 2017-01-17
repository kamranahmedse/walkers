<?php

namespace KamranAhmed\Walkers\Player\Interfaces;

/**
 * Interface Player
 */
interface Player
{
    /**
     * @param int $health
     *
     * @return void
     */
    public function setHealth(int $health);

    /**
     * @return int
     */
    public function getHealth() : int;

    /**
     * @param int $experience
     *
     * @return void
     */
    public function setExperience(int $experience);

    /**
     * @return int
     */
    public function getExperience() : int;

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return bool
     */
    public function isAlive() : bool;

    /**
     * @param int $points
     *
     * @return void
     */
    public function addExperience(int $points = 10);

    /**
     * @return array
     */
    public function toArray() : array;
}
