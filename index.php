#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use KamranAhmed\Lost\Game;
use Symfony\Component\Console\Application;

/*
|--------------------------------------------------------------------------
| Bootstrap File
|--------------------------------------------------------------------------
|
| Initializes the application while setting the game command as the
| default command for the application and gets the ball rolling
|
*/
$app  = new Application('lost', '1.0.0');
$game = new Game();

$app->add($game);

$app->setDefaultCommand($game->getName(), true);
$app->run();
