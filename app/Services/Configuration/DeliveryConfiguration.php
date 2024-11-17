<?php

namespace App\Services\Configuration;

class DeliveryConfiguration
{
    private static float $defaultDeliveryPrice = 10.00;

    public static function getDefaultDeliveryPrice(): float
    {
        return self::$defaultDeliveryPrice;
    }

    public static function setDefaultDeliveryPrice(float $price): void
    {
        self::$defaultDeliveryPrice = $price;
    }
} 