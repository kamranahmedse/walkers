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

    /**
     * @return int
     */
    public function getHealth():int
    {
        return $this->health;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isAlive()
    {
        return !empty($this->health);
    }
}
