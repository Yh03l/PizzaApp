<?php

namespace App\Interfaces;

interface PriceStrategyInterface
{
    public function calculatePrice(
        float $basePrice, 
        float $ingredientsPrice, 
        int $quantity
    ): array;
} 