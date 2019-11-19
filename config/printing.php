<?php

return [
    'default' => 'camp',

    'clients' => [
        'camp' => [
            'driver' => 'printnode',
            'key' => env('PRINTNODE_CAMP_KEY'),
        ],

        'pcc' => [
            'driver' => 'printnode',
            'key' => env('PRINTNODE_PCC_KEY'),
        ],
    ],
];
