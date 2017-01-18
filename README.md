# Walkers

> A console based fan fiction RPG for [The Walking Dead](http://www.imdb.com/title/tt1520211/)

[![Build Status](https://travis-ci.org/kamranahmedse/walkers.svg?branch=master)](https://travis-ci.org/kamranahmedse/walkers)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kamranahmedse/walkers/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kamranahmedse/walkers/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/kamranahmedse/walkers/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kamranahmedse/walkers/?branch=master)

Walkers is a rough fan-fiction console based RPG for [The Walking Dead](http://www.imdb.com/title/tt1520211/) built in PHP and can be used for educational purposes on OOP and how to write clean and extensible code.

## Requirement

PHP >= 7

## Setup

Clone the repository and install the dependencies

```shell
composer install
```

Running the tests

```shell
./vendor/bin/phpunit tests
```

## How to play

Run the below command to start the game and then follow the steps stated below

```php
php index.php
```

### Step 1 - Choose the player

On the first screen, player will be presented with the option to choose player

![Players List](http://i.imgur.com/n4ZfmzH.png)

For the current release, each of the players have different healths. It was planned to give different abilities to each of the players (as you might be able to guess from the player class names i.e. `GunnerRick`, `NinjaMichone`, `RunnerGlenn` etc) but was plucked out of this release.

### Step 2 - Let the game begin

After choosing the player of your liking; the game begins

![Choose Door](http://i.imgur.com/hczkAU0.png?1)

On the top, player is shown a not-so-uplifting message and then the *HUD* showing the current progress including experience, health and the level that the player is on. Then the player is asked to chose a door, where some of doors are empty and some of them have randomly placed [walkers](http://www.telltalesonline.com/wp-content/uploads/2015/10/walking-dead-humans-walkers.jpg) in them.

- If the chosen door had walker in it; 
    - Walker gets to grab a bite of player while decreasing the health depending upon the walker
    - Player is returned back to same level with the same choice 
    - Walker are shuffled again in different doors

![Walker Door](http://i.imgur.com/T8Mf3QT.png?1)

- If the chosen door was empty; 
    - Player is **rewarded with experience**
    - Player is advanced to next level

![Empty Door](http://i.imgur.com/Ql5u5Iu.png)

The process keeps repeating unless, one of the below happens

#### i. Save and Exit

Player chooses to save game and exit. 

![Save and Exit](http://i.imgur.com/5txQDY3.png)

In which case, player will be asked if they want to resume the game or start a new game when the game is run the next time.

> Game data is stored in `/storage/game-data.wd` in encrypted-but-not-so-super-encrypted manner.

![Resume Game?](http://i.imgur.com/u3u1ZuB.png)

If the user chooses to restore game; it will begin from the last state (i.e. same level, health and experience). If not, it will begin from the first level.

#### ii. Exit

Player chooses to exit game.

#### iii. Player Dies

Player gets bitten by zombies again and again to the point that health becomes zero.

![](http://i.imgur.com/czr3qnD.png)

#### iv. Game is Completed

Final level is reached.

![](http://i.imgur.com/UyKyhue.png)

## How to extend

Please find the relevant details below
 
### What is where

- **/config** directory in the root contains any configuration and map files.
- **/src** is where all the magic happens
- **/src/Console** contains the contracts and implementations for console component to be used for logging and getting inputs. Currently having the implementation for [Symfony's console component](http://symfony.com/doc/current/components/console.html)
- **/src/Exceptions** Any exceptions to be thrown
- **/src/Player** has the contracts and implementations for `Player`. Also houses sample implementations for some [Walking Dead Characters](https://www.google.ae/search?q=walking+dead+cast&oq=walking+dead+cast&aqs=chrome..69i57j69i60j69i59j69i60j69i61j0.4479j0j1&sourceid=chrome&ie=UTF-8)
- **/src/Walker** houses the contracts and implementation for `Walkers` having sample implementation for some walkers.
- **/src/Storage** has everything relevant to storage. Currently there is JSON file storage support.
- **/src/Game.php** houses the game loop and acts as the controller for game.
- **/src/Map.php** has all the map specific details.
- **/src/Runner.php** Symfony command that initiates the game loop. Symfony's console component needs that.
- **/storage** contains any storage related data
- **/tests** of-course contain the test cases.
- **/index.php** is the file that bootstraps the game

### i. Extending Map - Adding or Modifying Levels

Head to the map file at `config/map.php` and follow the instructions at the top to add new level or modify the existing levels. To add a new level all you have to do is add it to the `levels` array in the map. A sample level may look like below:

```php
[
    'doorCount'        => 3,    // Doors on this level
    'players'          => [     // Array of allowed (if first) or unlocked (on other levels [TODO]) 
        'Rick - The Father' => GunnerRick::class,
        'Carl - The Kid'    => KidCarl::class,
    ],
    'walkers'          => [     // Array of walkers that will be put in a random door
        OneLegged::class,
    ],
    'experiencePoints' => 10,   // Experience points added on successful completion of level
],
```

For further details, please check the docs in `config/map.php`.

### ii. Changing Console Component

Say you would like to replace Symfony's console component with something else, just implement the interface `KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface` and pass the instance of it while initializing Game i.e.

```php
$game = new \KamranAhmed\Walkers\Game(ConsoleInterface $console ...);
$game->play();
```

### iii. Introducing New Player Types

Just implement the `KamranAhmed\Walkers\Player\Interfaces\Player` interface and use in the map or wherever you want.

### iv. Changing Storage Component

Currently there is `JsonStorage` but you can easily replace it with database or anything by implementing `\KamranAhmed\Walkers\Storage\Interfaces\GameStorage` and use it while initializing the game i.e. 

```php
$game = new \KamranAhmed\Walkers\Game(ConsoleInterface $console, Storage $storage, ...);
$game->play();
```

### v. New or Advanced Walkers

Just implement the `\KamranAhmed\Walkers\Walker\Interfaces\Walker` interface and use it. Also if you would want to modify the bite behavior, just override the `eat` method in the base walker class and implement your own in the walker.
