<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class DemoOrdersSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are some products
        if (Product::count() < 5) {
            // If no products, call existing Product seeder
            $this->call([\ProductSeeder::class]);
        }

        // Create 20 orders
        Order::factory()->count(20)->create()->each(function ($order) {
            // For each order create 1-4 order items
            $items = rand(1, 4);
            $total = 0;
            for ($i = 0; $i < $items; $i++) {
                $item = OrderItem::factory()->create(['order_id' => $order->id]);
                $total += $item->total_price;
            }
            // Use a valid status value
            $order->update(['total' => $total, 'status' => 'delivered']);
        });
    }
}
