<?php

namespace App\Interfaces;

interface DeliveryPriceInterface
{
    public function getDeliveryPrice(): float;
    public function isDeliveryFree(): bool;
} 