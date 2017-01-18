<?php

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Player\OldHershel;
use KamranAhmed\Walkers\Walker\Blind;
use KamranAhmed\Walkers\Walker\Deadly;
use KamranAhmed\Walkers\Walker\Grouchy;
use KamranAhmed\Walkers\Walker\OneLegged;

/*
|-----------------------------------------------------------------------------
| Map File
|-----------------------------------------------------------------------------
|
| Below is the map representation for the game. It must have the `levels` key
| which is an array of arrays. Each of the level has some properties that
| define how the level will look like.
|
| You can specify the following properties on level
|-----------------------------------------------------------------------------
|  - `doorCount` integer representing the number of doors on this level
|
|  - `players` an associative array where keys represent the title by which
|              the player will appear in menus etc and value representing the
|              player implementation.
|
|              Players defined in first level will be shown for the choice to
|              user when starting game players specified on each of the next
|              levels are the ones that will be unlocked when reaching that and
|              the user can then switch between them. TODO [Unlocking of players]
|
|  - `walkers` is an array of walkers on this level. Each of the walkers
|              will be randomly placed in one of the doors
|
|  - `experiencePoints` integer representing the experience points awarded to
|              user when crossing this level alive
|
*/
return [
    'levels' => [
        [
            'doorCount'        => 3,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
            ],
            'walkers'          => [
                OneLegged::class,
            ],
            'experiencePoints' => 10,
        ],
        [
            'doorCount'        => 5,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
                'Hershel - Old Guy' => OldHershel::class,
            ],
            'walkers'          => [
                OneLegged::class,
                Deadly::class,
            ],
            'experiencePoints' => 20,
        ],
        [
            'doorCount'        => 6,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
                'Hershel - Old Guy' => OldHershel::class,
            ],
            'walkers'          => [
                OneLegged::class,
                Deadly::class,
                Blind::class,
                Grouchy::class,
            ],
            'experiencePoints' => 20,
        ],
        [
            'doorCount'        => 7,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
                'Hershel - Old Guy' => OldHershel::class,
            ],
            'walkers'          => [
                OneLegged::class,
                Deadly::class,
                Blind::class,
                Grouchy::class,
            ],
            'experiencePoints' => 20,
        ],
    ],
];
