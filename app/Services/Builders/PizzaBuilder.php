<?php

namespace App\Services\Builders;

use App\Interfaces\PizzaBuilderInterface;
use App\Models\Ingredient;
use App\Models\Pizza;

class PizzaBuilder implements PizzaBuilderInterface
{
    private array $pizza = [];
    private array $ingredients = [];
    private float $basePrice = 0;
    private float $ingredientsPrice = 0;

    public function setBase(string $base, float $price): self
    {
        $this->pizza['base'] = $base;
        $this->basePrice = $price;
        return $this;
    }

    public function addIngredient(int $ingredientId): self
    {
        $ingredient = Ingredient::findOrFail($ingredientId);
        $this->ingredients[] = $ingredient;
        $this->ingredientsPrice += $ingredient->price;
        return $this;
    }

    public function removeIngredient(int $ingredientId): self
    {
        $this->ingredients = array_filter($this->ingredients, function($ingredient) use ($ingredientId) {
            if ($ingredient->id === $ingredientId) {
                $this->ingredientsPrice -= $ingredient->price;
                return false;
            }
            return true;
        });
        return $this;
    }

    public function getTotalPrice(): float
    {
        return $this->basePrice + $this->ingredientsPrice;
    }

    public function build(): array
    {
        return [
            'base' => $this->pizza['base'],
            'base_price' => $this->basePrice,
            'ingredients' => $this->ingredients,
            'ingredients_price' => $this->ingredientsPrice,
            'total_price' => $this->getTotalPrice()
        ];
    }
} 