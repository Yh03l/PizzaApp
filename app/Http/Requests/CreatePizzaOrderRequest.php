<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePizzaOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day' => 'required|string|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'pizzas' => 'required|array|min:1',
            'pizzas.*.type' => 'required|in:preset,custom',
            'pizzas.*.quantity' => 'required|integer|min:1',
            'pizzas.*.pizza_id' => 'required_if:pizzas.*.type,preset|exists:pizzas,id',
            'pizzas.*.ingredients' => 'required_if:pizzas.*.type,custom|array',
            'pizzas.*.ingredients.*' => 'exists:ingredients,id',
            'pizzas.*.extra_ingredients' => 'sometimes|array',
            'pizzas.*.extra_ingredients.*' => 'exists:ingredients,id',
            'pizzas.*.remove_ingredients' => 'sometimes|array',
            'pizzas.*.remove_ingredients.*' => 'exists:ingredients,id'
        ];
    }

    public function messages(): array
    {
        return [
            'pizzas.required' => 'Debe especificar al menos una pizza',
            'pizzas.*.type.required' => 'Debe especificar el tipo de pizza (preset o custom)',
            'pizzas.*.quantity.required' => 'Debe especificar la cantidad para cada pizza',
            'pizzas.*.quantity.min' => 'La cantidad mínima es 1 pizza'
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'status' => 'error',
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422));
    }
} 