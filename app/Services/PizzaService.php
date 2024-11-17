<?php

namespace App\Services;

use App\Models\Pizza;
use App\Models\Ingredient;
use App\Services\Builders\PizzaBuilder;
use App\Interfaces\PriceStrategyInterface;
use App\Services\Strategies\RegularPriceStrategy;
use App\Services\Strategies\TwoForOnePriceStrategy;
use App\Services\Strategies\FreeDeliveryPriceStrategy;

class PizzaService
{
    public function getPriceStrategy(?string $day): PriceStrategyInterface
    {
        return match (strtolower($day)) {
            'martes', 'miercoles' => new TwoForOnePriceStrategy(),
            'jueves' => new FreeDeliveryPriceStrategy(),
            default => new RegularPriceStrategy(),
        };
    }

    public function buildCustomPizza(array $data): array
    {
        $builder = new PizzaBuilder();
        $builder->setBase('Masa Base', 20.00);

        foreach ($data['ingredients'] as $ingredientId) {
            $builder->addIngredient($ingredientId);
        }

        return $builder->build();
    }

    public function buildPresetPizza(Pizza $pizza, array $extraIngredients = [], array $removeIngredients = []): array
    {
        $builder = new PizzaBuilder();
        $builder->setBase('Masa Base', $pizza->base_price);

        // Agregar ingredientes base de la pizza
        foreach ($pizza->ingredients as $ingredient) {
            if (!in_array($ingredient->id, $removeIngredients)) {
                $builder->addIngredient($ingredient->id);
            }
        }

        // Agregar ingredientes extra
        foreach ($extraIngredients as $ingredientId) {
            $builder->addIngredient($ingredientId);
        }

        return $builder->build();
    }
} 