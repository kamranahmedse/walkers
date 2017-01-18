<?php

namespace KamranAhmed\Walkers;

use KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface;
use KamranAhmed\Walkers\Player\Interfaces\Player;
use KamranAhmed\Walkers\Storage\Interfaces\GameStorage;
use KamranAhmed\Walkers\Walker\Interfaces\Walker;

/**
 * Class Game
 *
 * @package KamranAhmed\Walkers
 */
class Game
{
    const SAVE_EXIT = 'Save and Exit';
    const EXIT      = 'Exit';

    const MENU_ACTIONS = [
        self::SAVE_EXIT,
        self::EXIT,
    ];

    /** @var \KamranAhmed\Walkers\Map */
    protected $map;

    /** @var ConsoleInterface */
    protected $console;

    /** @var Player */
    protected $player;

    /** @var GameStorage $storage */
    protected $storage;

    /**
     * Map constructor.
     *
     * @param \KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface $console
     * @param \KamranAhmed\Walkers\Storage\Interfaces\GameStorage      $storage
     * @param \KamranAhmed\Walkers\Map                                 $map
     */
    public function __construct(ConsoleInterface $console, GameStorage $storage, Map $map)
    {
        $this->console = $console;
        $this->storage = $storage;
        $this->map     = $map;
    }

    /**
     * Initializes the game i.e. basic configuration and the game loop
     *
     * @return void
     */
    public function play()
    {
        $this->initialize();
        $this->gameLoop();
        $this->endGame();
    }

    /**
     * Initializes the game
     *
     * @return void
     */
    protected function initialize()
    {
        $this->showWelcome();

        // Restore game if necessary or load the
        // first level while asking for player
        if ($this->storage->hasSavedGame() && $this->shouldRestore()) {
            $this->restoreSavedGame();
        } else {
            $this->map->loadLevel(0);
            $this->choosePlayer();
        }

        // Remove the saved game if any; because user
        // has started playing if it was available
        $this->storage->removeSavedGame();

        $this->console->print('You will be shown some doors!');
        $this->console->print('Carefully choose a door while praying that you do not come across a Walker!');

        $this->console->breakLine();
    }

    /**
     * Shows the game title and welcome message
     */
    public function showWelcome()
    {
        $this->console->printTitle('The Walking Dead');
        $this->console->print('Welcome to the world of the dead, see if you can ditch your way through the walkers towards the sanctuary.');
    }

    /**
     * Asks the user to see if the game should be restored or not
     *
     * @return bool
     */
    protected function shouldRestore()
    {
        $restoreGame = $this->console->askChoice('Saved game found. Would you like to restore it?', ['Yes', 'No']);

        return $restoreGame === 'Yes';
    }

    /**
     * Restores the saved game if available and
     * the choice was made to restore.
     *
     * @return void
     */
    protected function restoreSavedGame()
    {
        $gameData = $this->storage->getSavedGame();

        // Set the player information
        $this->player = new $gameData['player']['class'];

        $this->player->setExperience($gameData['player']['experience']);
        $this->player->setHealth($gameData['player']['health']);

        // Load the specified map level
        $this->map->loadLevel($gameData['level']);

        $this->storage->removeSavedGame();
    }

    /**
     * Asks for the player choice out of the
     * available players
     */
    public function choosePlayer()
    {
        $players = $this->map->getPlayers();
        $choice  = $this->console->askChoice('Chose your player?', array_keys($players));

        $this->player = new $players[$choice];

        $this->console->printTitle('Godspeed ' . $this->player->getName() . '!');
    }

    /**
     * Runs the game loop till the final level has reached
     * or the player is dead
     *
     * @throws \KamranAhmed\Walkers\Exceptions\InvalidLevelException
     */
    public function gameLoop()
    {
        do {
            $this->console->printTitle('Level ' . ($this->map->getCurrentLevel()));
            $this->showProgress();

            $doors   = $this->map->getDoors(true);
            $choices = $this->generateDoorMenu($doors);

            $choice = $this->console->askChoice('Carefully choose the door to enter!', $choices);

            // Perform the menu action
            if ($this->isMenuAction($choice)) {
                $this->performAction($choice);
            }
            // Door had walker in it?
            // Get the player bitten continue again to show the current level
            else if ($this->isWalkerDoor($doors, $choice)) {
                $doors[$choice]->eat($this->player);
                $this->console->printDanger('Bitten by ' . $doors[$choice]->getName() . '! Health decreased to ' . $this->player->getHealth());
            }
            // Door does not have any walker
            // Add the experience and advance to next level
            else {
                $this->console->printInfo('Phew! Nothing in that door!');
                $this->player->addExperience($this->map->getCurrentLevelExperience());

                // End the game loop if no level ahead
                if (!$this->map->canAdvance()) {
                    return;
                }

                $this->map->advance();
            }

        } while ($this->player->isAlive());
    }

    /**
     * Shows the current progress of the player
     * in tabular form
     */
    public function showProgress()
    {
        $this->console->printTable(
            ['Level', 'Experience', 'Health'],
            [
                [$this->map->getCurrentLevel(), $this->player->getExperience(), $this->player->getHealth()],
            ]
        );
    }

    /**
     * Returns the doors with the menu items
     *
     * @param array $doors
     *
     * @return array
     */
    public function generateDoorMenu(array $doors)
    {
        $doorNames = array_keys($doors);

        return array_merge($doorNames, self::MENU_ACTIONS);
    }

    /**
     * @param $choice
     *
     * @return bool
     */
    protected function isMenuAction($choice) : bool
    {
        return in_array($choice, self::MENU_ACTIONS);
    }

    /**
     * Performs the specified action, if possible
     *
     * @param $action
     *
     * @return void
     */
    public function performAction($action)
    {
        switch ($action) {
            case static::SAVE_EXIT:
                $this->saveGame();
                $this->console->printSuccess('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
                exit(0);
            case static::EXIT:
                $this->console->printSuccess('Bye bye ' . $this->player->getName() . '! Walkers will be waiting for you');
                exit(0);
        }
    }

    /**
     * Saves the game to storage
     *
     * @return void
     */
    public function saveGame()
    {
        $this->storage->saveGame(
            $this->player,
            $this->map
        );
    }

    /**
     * @param array  $doors
     * @param string $door
     *
     * @return bool
     */
    public function isWalkerDoor(array $doors, string $door) : bool
    {
        return !empty($doors[$door]) && ($doors[$door] instanceof Walker);
    }

    /**
     * Ends the game while providing the relevant
     */
    public function endGame()
    {
        if ($this->player->isAlive()) {
            $this->console->printSuccess('Good work ' . $this->player->getName() . '! You have made it alive to the Sanctuary');
        } else {
            $this->console->printDanger('*Rest in peace ' . $this->player->getName() . '! You will be remembered*');
        }

        $this->showProgress();
        exit(0);
    }
}
