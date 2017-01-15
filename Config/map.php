<?php

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;

return [
    'levels'          => [
        [
            'doorCount'   => 3,
            'players'     => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
            ],
            'walkerCount' => 1,
            'walkerTypes' => [

            ],
        ],
    ],
    'playerInventory' => [
        GunnerRick::class => [

        ],
    ],
];
