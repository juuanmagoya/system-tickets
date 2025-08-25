<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\Support;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        

        return [
            'customer_id' => Customer::query()->exists()
            ? Customer::query()->inRandomOrder()->first()->id
            : Customer::factory()->create()->id,
            'support_id' => Support::query()->exists()
            ? Support::query()->inRandomOrder()->first()->id
            : Support::factory()->create()->id,
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['Open', 'In Progress', 'Closed']),
        ];
    }
        //$customer = Customer::factory()->create();
        //$support = Support::factory()->create();
        //'customer_id' => $customer->id,
        //'support_id' => $support->id,
        //'description' => $this->faker->sentence(),
        //'status' => $this->faker->randomElement(['Open', 'In Progress', 'Closed']),
}
