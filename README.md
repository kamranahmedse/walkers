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
   

