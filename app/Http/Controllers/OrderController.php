<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use App\Models\Ingredient;
use App\Services\OrderService;
use App\Http\Resources\PizzaResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\IngredientResource;
use App\Http\Requests\CreatePizzaOrderRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => PizzaResource::collection(Pizza::with('ingredients')->get())
        ]);
    }

    public function ingredients(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => IngredientResource::collection(Ingredient::all())
        ]);
    }

    public function createOrder(CreatePizzaOrderRequest $request): JsonResponse
    {
        try {
            $orderData = $this->orderService->createPizzaOrder($request->validated());
            
            return response()->json([
                'status' => 'success',
                'data' => new OrderResource($orderData)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}
