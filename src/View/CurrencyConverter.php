<?php

namespace Raketa\BackendTestTask\View;

class CurrencyConverter
{
    public static function convertToMajor(int $price): float
    {
        return $price / 100;
    }

    public static function convertToMinor(float $price): int
    {
        return $price * 100;
    }
}
