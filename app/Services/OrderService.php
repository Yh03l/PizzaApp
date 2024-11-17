<?php

namespace App\Services;

use App\Models\Pizza;
use App\Services\PizzaService;
use App\Services\DeliveryService;

class OrderService
{
    public function __construct(
        private readonly PizzaService $pizzaService
    ) {}

    public function createPizzaOrder(array $validated): array
    {
        $pizzaOrders = [];
        $totalAmount = 0;
        $totalDiscount = 0;
        $deliveryService = new DeliveryService($validated['day']);
        $deliveryPrice = $deliveryService->getDeliveryPrice();
        
        foreach ($validated['pizzas'] as $pizzaOrder) {
            $pizzaData = $this->processSinglePizza($pizzaOrder, $validated['day']);
            $pizzaOrders[] = $pizzaData;
            
            $totalAmount += $pizzaData['detalle_precios']['subtotal'];
            $totalDiscount += $pizzaData['detalle_precios']['descuento'];
        }

        $finalAmount = $totalAmount - $totalDiscount + $deliveryPrice;
        
        return [
            'orden' => [
                'pizzas' => $pizzaOrders,
                'resumen' => [
                    'subtotal' => number_format($totalAmount, 2) . ' Bs.',
                    'descuento_total' => number_format($totalDiscount, 2) . ' Bs.',
                    'costo_delivery' => number_format($deliveryPrice, 2) . ' Bs.',
                    'total' => number_format($finalAmount, 2) . ' Bs.'
                ],
                'promocion' => [
                    'dia' => ucfirst($validated['day']),
                    'delivery_gratis' => $deliveryService->isDeliveryFree(),
                ]
            ]
        ];
    }

    private function processSinglePizza(array $pizzaOrder, string $day): array
    {
        if ($pizzaOrder['type'] === 'preset') {
            return $this->createPresetPizzaOrder($pizzaOrder, $day);
        }
        
        return $this->createCustomPizzaOrder($pizzaOrder, $day);
    }

    private function createPresetPizzaOrder(array $data, string $day): array
    {
        $pizza = Pizza::with('ingredients')->findOrFail($data['pizza_id']);
        $pizzaData = $this->pizzaService->buildPresetPizza(
            $pizza,
            $data['extra_ingredients'] ?? [],
            $data['remove_ingredients'] ?? []
        );
        
        return $this->preparePizzaOrderData(
            $pizzaData,
            'Preestablecida: ' . $pizza->name,
            $day,
            $data['quantity']
        );
    }

    private function createCustomPizzaOrder(array $data, string $day): array
    {
        $pizzaData = $this->pizzaService->buildCustomPizza($data);
        
        return $this->preparePizzaOrderData(
            $pizzaData,
            'Personalizada',
            $day,
            $data['quantity']
        );
    }

    private function preparePizzaOrderData(array $pizzaData, string $pizzaType, string $day, int $quantity): array
    {
        $priceDetails = $this->pizzaService
            ->getPriceStrategy($day)
            ->calculatePrice(
                $pizzaData['base_price'], 
                $pizzaData['ingredients_price'], 
                $quantity
            );

        return [
            'tipo_pizza' => $pizzaType,
            'cantidad' => $quantity,
            'base' => [
                'nombre' => $pizzaData['base'],
                'precio_unitario' => number_format($pizzaData['base_price'], 2) . ' Bs.'
            ],
            'ingredientes' => $pizzaData['ingredients'],
            'detalle_precios' => [
                'precio_unitario' => $priceDetails['unit_price'],
                'precio_unitario_formato' => number_format($priceDetails['unit_price'], 2) . ' Bs.',
                'cantidad' => $priceDetails['quantity'],
                'subtotal' => $priceDetails['subtotal'],
                'subtotal_formato' => number_format($priceDetails['subtotal'], 2) . ' Bs.',
                'descuento' => $priceDetails['discount'],
                'descuento_formato' => number_format($priceDetails['discount'], 2) . ' Bs.',
                'total' => $priceDetails['total'],
                'total_formato' => number_format($priceDetails['total'], 2) . ' Bs.'
            ],
            'promocion' => [
                'dia' => ucfirst($day),
                'promocion_aplicada' => $priceDetails['promotion_applied'],
                'descuento_aplicado' => $priceDetails['discount'],
                'descuento_aplicado_formato' => number_format($priceDetails['discount'], 2) . ' Bs.'
            ]
        ];
    }

    private function getPromocionDetalle(string $day, int $quantity): string
    {
        return match (strtolower($day)) {
            'martes', 'miercoles' => $this->getTwoForOneDetail($quantity),
            'jueves' => "Delivery gratis para $quantity pizza(s)",
            default => "Sin promoción aplicable para este día"
        };
    }

    private function getTwoForOneDetail(int $quantity): string
    {
        $pairsCount = floor($quantity / 2);
        $remainingPizzas = $quantity % 2;
        
        return "Se aplica 2x1 en $pairsCount pares de pizzas" . 
               ($remainingPizzas ? " (1 pizza sin promoción)" : "");
    }
} 