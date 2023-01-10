<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TransferStockToWarehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TransferStockToWarehouse>
 */
final class TransferStockToWarehouseFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = TransferStockToWarehouse::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'status' => $this->faker->boolean,
            'branch_id' => \App\Models\Branch::factory(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'total_item' => $this->faker->randomFloat(),
            'total_send_qty' => $this->faker->randomFloat(),
            'total_received_qty' => $this->faker->randomFloat(),
            'net_total_amount' => $this->faker->randomFloat(),
            'shipping_charge' => $this->faker->randomFloat(),
            'additional_note' => $this->faker->text,
            'receiver_note' => $this->faker->text,
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'admin_id' => $this->faker->randomNumber(),
            'report_date' => $this->faker->word,
        ];
    }
}
