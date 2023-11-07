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

function isDigicelNumber(float $phoneNumber)
{
    if ($phoneNumber >= 5977100000 && $phoneNumber <= 5977299999) {
        return true;
    }

    if ($phoneNumber >= 5977400000 && $phoneNumber <= 5977499999) {
        return true;
    }

    if ($phoneNumber >= 5977600000 && $phoneNumber <= 5977699999) {
        return true;
    }

    if ($phoneNumber >= 5978100000 && $phoneNumber <= 5978299999) {
        return true;
    }

    return false;
}

function isTelesurNumber(int $phoneNumber)
{
    if ($phoneNumber >= 5977500000 && $phoneNumber <= 5977599999) {
        return true;
    }

    if ($phoneNumber >= 5977700000 && $phoneNumber <= 5977799999) {
        return true;
    }

    if ($phoneNumber >= 5978400000 && $phoneNumber <= 5978999999) {
        return true;
    }

    return false;
}
