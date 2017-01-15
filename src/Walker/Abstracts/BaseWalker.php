<?php

namespace KamranAhmed\Walkers\Walker\Abstracts;
use KamranAhmed\Walkers\Walker\Interfaces\Walker;

/**
 * Class BaseWalker
 *
 * @package KamranAhmed\Walkers\Walker\Abstracts
 */
abstract class BaseWalker implements Walker
{
    /** @var int */
    protected $health;

    /**
     * @return int
     */
    public function getHealth():int
    {
        return $this->health;
    }

    /**
     * @return bool
     */
    public function isAlive()
    {
        return !empty($this->health);
    }
}
