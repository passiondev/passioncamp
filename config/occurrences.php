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
        'closes' => '2020-04-06 23:59:59',
        'closed_message' => <<<'EOT'
            We are sorry to announce, but Passion Camp has been canceled for this coming June (due to COVID-19). This decision has not been made lightly or without thinking of every possibility there is to keep it going before coming to this conclusion. However, out of an abundance of caution and desire to prioritize the safety of each student and leader, we know that this is the best course of action at this time.

            While this summer is not going to look like we expected, we know that God is in control. Even in the midst of uncertainty and pain, He is using these days for His glory! Please do not hesitate to reach out to our Team with any questions ([students@passioncitychurch.com](mailto:students@passioncitychurch.com)).

            &mdash; The Passion Students Team
            EOT,
        'grades' => [6, 7, 8, 9, 10, 11, 12],
        'sold_out' => env('PCC_SOLD_OUT', false),
    ],
];
