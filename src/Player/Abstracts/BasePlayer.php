<?php

namespace KamranAhmed\Walkers\Player\Abstracts;

use KamranAhmed\Walkers\Player\Interfaces\Player;

/**
 * Class BasePlayer
 *
 * @package KamranAhmed\Walkers\Player\Abstracts
 */
abstract class BasePlayer implements Player
{
    /** @var int */
    protected $health = 0;

    /** @var string */
    protected $name;

    /** @var int */
    protected $experience = 0;

    /**
     * @param int $points
     *
     * @return void
     */
    public function addExperience(int $points = 10)
    {
        $this->experience += $points;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isAlive() : bool
    {
        return !empty($this->health);
    }

    /**
     * @return int
     */
    public function getExperience() : int
    {
        return $this->experience ?? 0;
    }

    /**
     * @param int $experience
     *
     * @return void
     */
    public function setExperience(int $experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return int
     */
    public function getHealth() : int
    {
        return $this->health;
    }

    /**
     * @param int $health
     *
     * @return void
     */
    public function setHealth(int $health)
    {
        $this->health = $health <= 0 ? 0 : $health;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        return [
            'experience' => $this->getExperience(),
            'health'     => $this->getHealth(),
            'class'      => get_called_class(),
        ];
    }
}
