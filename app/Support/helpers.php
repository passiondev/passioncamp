<?php

if (! function_exists('number_ordinal')) {
    function number_ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number.'th';
        }

        return $number.$ends[$number % 10];
    }
}
