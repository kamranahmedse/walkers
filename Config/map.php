<?php

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Walker\Harmless;

return [
    'levels'          => [
        [
            'doorCount' => 3,
            'players'   => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
            ],
            'walkers'   => [
                Harmless::class,
            ],
        ],
    ],
    'playerInventory' => [
        GunnerRick::class => [

        ],
    ],
];
