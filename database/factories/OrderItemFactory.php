<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        if (!$product) {
            // create a sample product if none exists
            $product = Product::factory()->create();
        }

        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        $quantity = $this->faker->numberBetween(1, 5);
        $unit_price = $product->price ?? $this->faker->numberBetween(1000, 20000);

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'artisan_id' => $product->artisan_id ?? null,
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'total_price' => $quantity * $unit_price,
            'product_data' => [
                'name' => $product->name,
            ],
        ];
    }
}
