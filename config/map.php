<?php

use KamranAhmed\Walkers\Player\GunnerRick;
use KamranAhmed\Walkers\Player\KidCarl;
use KamranAhmed\Walkers\Player\OldHershel;
use KamranAhmed\Walkers\Walker\Deadly;
use KamranAhmed\Walkers\Walker\HeadLess;

return [
    'levels' => [
        [
            'doorCount'        => 3,
            'players'          => [
                'Rick - The Father' => GunnerRick::class,
                'Carl - The Kid'    => KidCarl::class,
            ],
            'walkers'          => [
                HeadLess::class,
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
                HeadLess::class,
                Deadly::class,
            ],
            'experiencePoints' => 20,
        ],
    ],
];
