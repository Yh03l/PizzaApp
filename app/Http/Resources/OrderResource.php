<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'orden' => [
                'pizzas' => collect($this['orden']['pizzas'])->map(function ($pizza) {
                    return [
                        'tipo_pizza' => $pizza['tipo_pizza'],
                        'cantidad' => $pizza['cantidad'],
                        'base' => $pizza['base'],
                        'ingredientes' => IngredientResource::collection($pizza['ingredientes']),
                        'detalle_precios' => $pizza['detalle_precios'],
                        'promocion' => $pizza['promocion']
                    ];
                }),
                'resumen' => $this['orden']['resumen'],
                'promocion' => $this['orden']['promocion']
            ]
        ];
    }
} 