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

function isDigicelNumber(int $phoneNumber)
{
    if ($phoneNumber >= 7100000 && $phoneNumber <= 7299999) {
        return true;
    }

    if ($phoneNumber >= 7400000 && $phoneNumber <= 7499999) {
        return true;
    }

    if ($phoneNumber >= 7600000 && $phoneNumber <= 7699999) {
        return true;
    }

    if ($phoneNumber >= 8100000 && $phoneNumber <= 8299999) {
        return true;
    }

    return false;
}

function isTelesurNumber(int $phoneNumber)
{
    if ($phoneNumber >= 7500000 && $phoneNumber <= 7599999) {
        return true;
    }

    if ($phoneNumber >= 7700000 && $phoneNumber <= 7799999) {
        return true;
    }

    if ($phoneNumber >= 8400000 && $phoneNumber <= 8999999) {
        return true;
    }

    return false;
}
