<?php

return [
    'pcc' => [
        'slug' => 'pcc',
        'title' => 'Passion Camp',
        'title_short' => 'Passion Camp',
        'header_bg' => 'https://res.cloudinary.com/passionconf/image/upload/f_auto,q_auto,w_1200/v1583759901/passioncamp2020/pcc/bg.jpg',
        'header_fg' => 'https://res.cloudinary.com/passionconf/image/upload/f_auto,q_auto/v1583760654/passioncamp2020/pcc/fg.png',
        'pricing' => [
            '2020-01-01' => '399',
            // '2020-04-06' => '425',
            // '2020-05-04' => '450',
        ],
        'closes' => '2020-06-07 23:59:59',
        'closed_message' => 'Thank you so much for your interest in Passion Camp. At this time registration has closed. Please email [students@passioncitychurch.com](mailto:students@passioncitychurch.com) for further details or if you have any questions. Thanks so much!',
        'grades' => [6, 7, 8, 9, 10, 11, 12],
        'sold_out' => env('PCC_SOLD_OUT', false),
    ],
];
