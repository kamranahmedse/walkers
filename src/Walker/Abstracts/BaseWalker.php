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
    /** @var string */
    protected $name = 'Unknown';

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
}
