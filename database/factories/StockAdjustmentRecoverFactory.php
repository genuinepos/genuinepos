<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\StockAdjustmentRecover;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StockAdjustmentRecover>
 */
final class StockAdjustmentRecoverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockAdjustmentRecover::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'voucher_no' => $this->faker->word,
            'stock_adjustment_id' => \App\Models\StockAdjustment::factory(),
            'account_id' => \App\Models\Account::factory(),
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'recovered_amount' => $this->faker->randomFloat(),
            'note' => $this->faker->sentence,
            'report_date' => $this->faker->word,
        ];
    }
}
