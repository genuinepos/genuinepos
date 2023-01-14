<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MoneyReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\MoneyReceipt>
 */
final class MoneyReceiptFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = MoneyReceipt::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'amount' => $this->faker->randomFloat(),
            'customer_id' => \App\Models\Customer::factory(),
            'is_customer_name' => $this->faker->boolean,
            'branch_id' => \App\Models\Branch::factory(),
            'note' => $this->faker->sentence,
            'receiver' => $this->faker->word,
            'ac_details' => $this->faker->word,
            'is_date' => $this->faker->boolean,
            'is_header_less' => $this->faker->boolean,
            'gap_from_top' => $this->faker->randomNumber(),
            'date' => $this->faker->word,
            'month' => $this->faker->word,
        ];
    }
}
