<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'precio' => number_format($this->price, 2) . ' Bs.',
            'cantidad' => $this->when($this->pivot, fn() => $this->pivot->quantity)
        ];
    }
} 