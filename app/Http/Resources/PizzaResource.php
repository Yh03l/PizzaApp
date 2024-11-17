<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PizzaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalIngredientsPrice = $this->ingredients->sum('price');
        
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'precio_base' => number_format($this->base_price, 2) . ' Bs.',
            'ingredientes' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'precio_ingredientes' => number_format($totalIngredientsPrice, 2) . ' Bs.',
            'precio_total' => number_format($this->base_price + $totalIngredientsPrice, 2) . ' Bs.'
        ];
    }
} 