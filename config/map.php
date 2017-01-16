<?php

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Player\OldHershel;
use KamranAhmed\Walkers\Walker\Deadly;
use KamranAhmed\Walkers\Walker\Harmless;

return [
    'levels' => [
        [
            'doorCount'        => 3,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
            ],
            'walkers'          => [
                Harmless::class,
            ],
            'experiencePoints' => 0,
        ],
        [
            'doorCount'        => 5,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
                'Hershel - Old Guy' => OldHershel::class,
            ],
            'walkers'          => [
                Harmless::class,
                Deadly::class,
            ],
            'experiencePoints' => 10,
        ],
    ],
];
