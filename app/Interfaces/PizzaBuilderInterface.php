<?php

namespace App\Interfaces;

interface PizzaBuilderInterface
{
    public function setBase(string $base, float $price): self;
    public function addIngredient(int $ingredientId): self;
    public function removeIngredient(int $ingredientId): self;
    public function getTotalPrice(): float;
    public function build(): array;
} 