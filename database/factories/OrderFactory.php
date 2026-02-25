<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $total = $this->faker->numberBetween(1000, 50000);
        $itemCount = $this->faker->numberBetween(1, 4);

        return [
            'user_id' => $user->id,
            'order_number' => strtoupper(Str::random(10)),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'total' => $total,
            'item_count' => $itemCount,
            'shipping_address' => $this->faker->streetAddress(),
            'shipping_city' => $this->faker->city(),
            'shipping_neighborhood' => $this->faker->cityPrefix(),
            'shipping_phone' => $user->phone ?? $this->faker->phoneNumber(),
            'shipping_notes' => null,
            'billing_address' => $this->faker->streetAddress(),
            'billing_city' => $this->faker->city(),
            'payment_method' => $this->faker->randomElement(['kkiapay', 'mtn_momo', 'cash']),
            'payment_status' => 'pending',
            'payment_reference' => null,
            'shipping_fee' => 0,
            'tax_amount' => 0,
            'artisan_notes' => null,
            'tracking_number' => null,
            'estimated_delivery' => null,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
