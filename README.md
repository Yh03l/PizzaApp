# Servicio de Pizzas Personalizadas

API REST para el armado de pizzas personalizadas y preestablecidas.
## Requisitos

- PHP 8.2 o superior
- Composer
- SQLite
## Instalación

1. Clonar el repositorio
```
git clone https://github.com/Yh03l/PizzaApp.git
```

2. Instalar dependencias
```
composer install
```

3. Configurar el entorno
```
cp .env.example .env
php artisan key:generate
```

4. Configurar la base de datos SQLite (opcional, se puede usar la base de datos ya creada y saltar al paso 6)
```
touch database/database.sqlite
```

5. Ejecutar migraciones y seeders
```
php artisan migrate --seed
```

6. Iniciar servidor
```
php artisan serve
```

## Endpoints Disponibles

### 1. Listar Pizzas Preestablecidas
```
GET /api/pizzas
```
Retorna todas las pizzas preestablecidas con sus ingredientes.

### 2. Listar Ingredientes
```
GET /api/ingredients
```
Retorna todos los ingredientes disponibles con sus precios.

### 3. Crear Pedido
```
POST /api/orders
```

#### Parámetros para Pedido con Múltiples Pizzas
```json
{
    "day": "martes",
    "pizzas": [
        {
            "type": "preset",
            "pizza_id": 1,
            "quantity": 2,
            "extra_ingredients": [3, 4],
            "remove_ingredients": [1]
        },
        {
            "type": "custom",
            "quantity": 1,
            "ingredients": [1, 2, 3, 4]
        }
    ]
}
```

## Promociones

- Martes y Miércoles: 2x1 en todas las pizzas
- Jueves: Delivery gratis (se aplica al pedido completo)

## Características Principales

1. **Soporte para Múltiples Pizzas por Pedido**
   - Permite ordenar diferentes tipos de pizzas en un solo pedido
   - Cada pizza puede ser personalizada o preestablecida
   - Manejo independiente de promociones por pizza

2. **Cálculo de Delivery**
   - El costo de delivery se aplica por pedido, no por pizza
   - Delivery gratis los jueves
   - Costo de delivery unificado independiente de la cantidad de pizzas

3. **Sistema de Promociones**
   - Promociones aplicadas individualmente a cada pizza
   - Delivery gratis aplicado al pedido completo
   - Cálculo automático de descuentos 2x1


## Estructura del Proyecto

El proyecto sigue los principios SOLID y utiliza los siguientes patrones de diseño:

### Patrones de Diseño Implementados

1. **Builder Pattern**
   - Utilizado para la construcción de pizzas personalizadas y preestablecidas
   - Permite agregar y remover ingredientes de manera fluida
   - Facilita el cálculo de precios durante la construcción

2. **Strategy Pattern**
   - Implementado para el manejo de diferentes estrategias de precios
   - Permite calcular precios según el día de la semana
   - Facilita la adición de nuevas estrategias de precios

### Principios SOLID

1. **Single Responsibility Principle (SRP)**
   - Cada clase tiene una única responsabilidad
   - Separación clara entre modelos, servicios y controladores

2. **Open/Closed Principle (OCP)**
   - Las clases están abiertas para extensión pero cerradas para modificación
   - Nuevas estrategias de precios pueden ser agregadas sin modificar el código existente

3. **Liskov Substitution Principle (LSP)**
   - Las estrategias de precios son intercambiables
   - Todas las implementaciones respetan el contrato de la interfaz

4. **Interface Segregation Principle (ISP)**
   - Interfaces específicas para cada propósito
   - Los clientes no dependen de interfaces que no usan

5. **Dependency Inversion Principle (DIP)**
   - Las dependencias se inyectan en las clases
   - El código depende de abstracciones, no de implementaciones concretas



## Especificación de Endpoints

### 1. Listar Pizzas Preestablecidas
```bash
GET /api/pizzas
```
No requiere parámetros.

**Respuesta**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nombre": "Margherita",
            "precio_base": "30.00 Bs.",
            "ingredientes": [
                {
                    "id": 1,
                    "nombre": "Masa Base",
                    "precio": "20.00 Bs."
                }
            ],
            "precio_ingredientes": "45.00 Bs.",
            "precio_total": "75.00 Bs."
        }
    ]
}
```

### 2. Listar Ingredientes
```bash
GET /api/ingredients
```
No requiere parámetros.

**Respuesta**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "nombre": "Masa Base",
            "precio": "20.00 Bs."
        }
    ]
}
```

### 3. Crear Pedido
```bash
POST /api/orders
```

**Parámetros del Json Body**

#### Parámetros
| Campo | Tipo | Requerido | Descripción |
|-------|------|-----------|-------------|
| day | string | Sí | Día de la semana (lunes, martes, miercoles, jueves, viernes, sabado, domingo) |
| pizzas | array | Sí | Array de pizzas a ordenar |
| pizzas[].type | string | Sí | Tipo de pizza (preset/custom) |
| pizzas[].quantity | integer | Sí | Cantidad de pizzas (mín: 1) |
| pizzas[].pizza_id | integer | Solo si type=preset | ID de pizza preestablecida |
| pizzas[].ingredients | array | Solo si type=custom | IDs de ingredientes |
| pizzas[].extra_ingredients | array | No | IDs de ingredientes adicionales |
| pizzas[].remove_ingredients | array | No | IDs de ingredientes a remover |

#### Ejemplo de Request

**Ejemplos de Requests Válidos**

1. Pedido de Pizza Preestablecida con Modificaciones
```json
{
    "day": "martes",
    "pizzas": [
        {
            "type": "preset",
            "pizza_id": 1,
            "quantity": 2,
            "extra_ingredients": [3, 4],
            "remove_ingredients": [1]
        }
    ]
}
```

2. Pedido de Pizza Personalizada
```json
{
    "day": "miercoles",
    "pizzas": [
        {
            "type": "custom",
            "quantity": 1,
            "ingredients": [1, 2, 3, 4]
        }
    ]
}
```

3. Pedido Mixto
```json
{
    "day": "jueves",
    "pizzas": [
        {
            "type": "preset",
            "pizza_id": 1,
            "quantity": 2
        },
        {
            "type": "custom",
            "quantity": 1,
            "ingredients": [1, 2, 3]
        }
    ]
}
```

**Respuesta Exitosa**
```json
{
    "status": "success",
    "data": {
        "orden": {
            "pizzas": [
                {
                    "tipo_pizza": "string",
                    "cantidad": "integer",
                    "base": {
                        "nombre": "string",
                        "precio_unitario": "string (formato: XX.XX Bs.)"
                    },
                    "ingredientes": [
                        {
                            "id": "integer",
                            "nombre": "string",
                            "precio": "string (formato: XX.XX Bs.)"
                        }
                    ],
                    "detalle_precios": {
                        "precio_unitario": "float",
                        "precio_unitario_formato": "string (formato: XX.XX Bs.)",
                        "cantidad": "integer",
                        "subtotal": "float",
                        "subtotal_formato": "string (formato: XX.XX Bs.)",
                        "descuento": "float",
                        "descuento_formato": "string (formato: XX.XX Bs.)",
                        "total": "float",
                        "total_formato": "string (formato: XX.XX Bs.)"
                    },
                    "promocion": {
                        "promocion_aplicada": "string",
                        "descuento_aplicado": "float",
                        "descuento_aplicado_formato": "string (formato: XX.XX Bs.)"
                    }
                }
            ],
            "resumen": {
                "subtotal": "string (formato: XX.XX Bs.)",
                "descuento_total": "string (formato: XX.XX Bs.)",
                "costo_delivery": "string (formato: XX.XX Bs.)",
                "total": "string (formato: XX.XX Bs.)"
            },
            "promocion": {
                "dia": "string",
                "delivery_gratis": "boolean"
            }
        }
    }
}
```

**Respuestas de Error**
```json
{
    "status": "error",
    "message": "Error de validación",
    "errors": {
        "campo": ["mensaje de error"]
    }
}
```

## Extras

- Para probar los endpoints se puede usar la herramienta de Postman.
- Para ello, importe el archivo `Pizzas.postman_collection.json` ubicado en la carpeta raiz del proyecto.
