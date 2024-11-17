<?php

namespace App\Services\Strategies;

use App\Interfaces\PriceStrategyInterface;

class TwoForOnePriceStrategy implements PriceStrategyInterface
{
    public function calculatePrice(
        float $basePrice, 
        float $ingredientsPrice, 
        int $quantity
    ): array {
        $unitPrice = $basePrice + $ingredientsPrice;
        $subtotal = $unitPrice * $quantity;
        $discount = $unitPrice * floor($quantity / 2);
        
        return [
            'unit_price' => $unitPrice,
            'base_price' => $basePrice,
            'ingredients_price' => $ingredientsPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount,
            'promotion_applied' => 'Promoción 2x1'
        ];
    }
} 