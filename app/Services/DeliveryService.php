<?php

namespace App\Services;

use App\Interfaces\DeliveryPriceInterface;
use App\Services\Configuration\DeliveryConfiguration;

class DeliveryService implements DeliveryPriceInterface
{
    private bool $isFreeDelivery;

    public function __construct(string $day)
    {
        $this->isFreeDelivery = strtolower($day) === 'jueves';
    }

    public function getDeliveryPrice(): float
    {
        return $this->isDeliveryFree() ? 0 : DeliveryConfiguration::getDefaultDeliveryPrice();
    }

    public function isDeliveryFree(): bool
    {
        return $this->isFreeDelivery;
    }
} 