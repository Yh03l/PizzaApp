<?php

namespace Database\Seeders;

use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class PizzaSeeder extends Seeder
{
    public function run(): void
    {
        $pizzas = [
            [
                'name' => 'Margherita',
                'base_price' => 30.00,
                'ingredients' => [
                    'Masa Base',
                    'Salsa de Tomate',
                    'Mozzarella',
                    'Albahaca'
                ]
            ],
            [
                'name' => 'Pepperoni',
                'base_price' => 35.00,
                'ingredients' => [
                    'Masa Base',
                    'Salsa de Tomate',
                    'Mozzarella',
                    'Pepperoni'
                ]
            ],
            [
                'name' => 'Hawaiana',
                'base_price' => 40.00,
                'ingredients' => [
                    'Masa Base',
                    'Salsa de Tomate',
                    'Mozzarella',
                    'Jamón',
                    'Piña'
                ]
            ],
            [
                'name' => 'Cuatro Quesos',
                'base_price' => 45.00,
                'ingredients' => [
                    'Masa Base',
                    'Mozzarella',
                    'Queso Parmesano',
                    'Queso Gorgonzola',
                    'Queso Fontina'
                ]
            ]
        ];

        foreach ($pizzas as $pizzaData) {
            $pizza = Pizza::create([
                'name' => $pizzaData['name'],
                'base_price' => $pizzaData['base_price']
            ]);

            foreach ($pizzaData['ingredients'] as $ingredientName) {
                $ingredient = Ingredient::where('name', $ingredientName)->first();
                if ($ingredient) {
                    $pizza->ingredients()->attach($ingredient->id, ['quantity' => 1]);
                }
            }
        }
    }
}
