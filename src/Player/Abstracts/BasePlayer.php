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
    protected $health;

    /** @var string */
    protected $name;

    /** @var int */
    protected $experience = 0;

    /**
     * @return int
     */
    public function getHealth():int
    {
        return $this->health;
    }

    /**
     * @return int
     */
    public function getExperience()
    {
        return $this->experience;
    }

    public function addExperience(int $points = 10)
    {
        $this->experience += $points;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isAlive():bool
    {
        return !empty($this->health);
    }

    public function toArray():array
    {
        return [
            'experience' => $this->getExperience(),
            'health'     => $this->getHealth(),
            'class'      => get_class(),
        ];
    }
}
