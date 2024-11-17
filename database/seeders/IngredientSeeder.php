<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Masa Base', 'price' => 20.00],
            ['name' => 'Salsa de Tomate', 'price' => 5.00],
            ['name' => 'Mozzarella', 'price' => 10.00],
            ['name' => 'Pepperoni', 'price' => 15.00],
            ['name' => 'Jamón', 'price' => 12.00],
            ['name' => 'Piña', 'price' => 8.00],
            ['name' => 'Champiñones', 'price' => 7.00],
            ['name' => 'Pimientos', 'price' => 5.00],
            ['name' => 'Cebolla', 'price' => 4.00],
            ['name' => 'Aceitunas', 'price' => 6.00],
            ['name' => 'Albahaca', 'price' => 3.00],
            ['name' => 'Queso Parmesano', 'price' => 12.00],
            ['name' => 'Queso Gorgonzola', 'price' => 15.00],
            ['name' => 'Queso Fontina', 'price' => 14.00],
            ['name' => 'Pollo', 'price' => 13.00],
        ];

        DB::table('ingredients')->insert($ingredients);
    }
}
