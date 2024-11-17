<?php

namespace App\Services\Strategies;

use App\Interfaces\PriceStrategyInterface;

class FreeDeliveryPriceStrategy implements PriceStrategyInterface
{
    public function calculatePrice(
        float $basePrice, 
        float $ingredientsPrice, 
        int $quantity
    ): array {
        $unitPrice = $basePrice + $ingredientsPrice;
        $subtotal = $unitPrice * $quantity;
        
        return [
            'unit_price' => $unitPrice,
            'base_price' => $basePrice,
            'ingredients_price' => $ingredientsPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
            'promotion_applied' => 'Delivery Gratis'
        ];
    }
}