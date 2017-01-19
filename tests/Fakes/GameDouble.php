<?php

namespace KamranAhmed\Tests\Fakes;

use BadMethodCallException;
use Exception;
use KamranAhmed\Walkers\Console\Interfaces\Console;
use KamranAhmed\Walkers\Game;
use KamranAhmed\Walkers\Map;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;

/**
 * Class GameDouble
 *
 * @method initialize()
 * @method showWelcome()
 * @method restoreSavedGame()
 * @method choosePlayer()
 * @method shouldRestore()
 * @method gameLoop()
 * @method showProgress()
 * @method generateDoorMenu($doors)
 * @method isMenuAction($choice)
 * @method performAction($action)
 * @method isWalkerDoor($doors, $choice)
 * @method endGame()
 * @method saveGame()
 *
 * @property Map         $map
 * @property Console     $console
 * @property Player      $player
 * @property GameStorage $storage
 *
 * @package KamranAhmed\Tests
 */
class GameDouble extends Game
{
    /**
     * @param $name
     * @param $value
     *
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            throw new Exception('Undefined property ' . $name);
        }

        $this->{$name} = $value;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new Exception('Undefined property ' . $name);
        }

        return $this->{$name};
    }

    /**
     * @param       $method
     * @param array $args
     *
     * @return mixed
     */
    public function __call($method, array $args = [])
    {
        if (!method_exists($this, $method)) {
            throw new BadMethodCallException('Undefined method ' . $method);
        }

        return call_user_func_array([$this, $method], $args);
    }
}
