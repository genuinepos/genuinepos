<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseOrderProductReceive;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseOrderProductReceive>
 */
final class PurchaseOrderProductReceiveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseOrderProductReceive::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_product_id' => $this->faker->randomNumber(),
            'delivery_note_heading' => $this->faker->word,
            'lot_number' => $this->faker->randomNumber(),
            'received_date' => $this->faker->word,
            'qty_received' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
