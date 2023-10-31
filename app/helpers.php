<?php

function ordinalize(int $number)
{
    $suffixes = ['st', 'nd', 'rd', 'th'];
    $suffix = $suffixes[$number % 100 - 11];
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        $suffix = 'th';
    }
    return $number . $suffix;
}
