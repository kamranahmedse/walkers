#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use KamranAhmed\Walkers\Runner;
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
$app  = new Application('walkers', '1.0.0');
$game = new Runner();

$app->add($game);

$app->setDefaultCommand($game->getName(), true);
$app->run();
