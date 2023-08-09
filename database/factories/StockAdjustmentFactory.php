<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StockAdjustment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StockAdjustment>
 */
final class StockAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockAdjustment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock_adjustment_account_id' => $this->faker->randomNumber(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'invoice_id' => $this->faker->word,
            'total_item' => $this->faker->randomNumber(),
            'total_qty' => $this->faker->randomFloat(),
            'net_total_amount' => $this->faker->randomFloat(),
            'recovered_amount' => $this->faker->randomFloat(),
            'type' => $this->faker->boolean,
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'reason' => $this->faker->word,
            'report_date_ts' => $this->faker->word,
            'admin_id' => \App\Models\User::factory(),
        ];
    }
}
