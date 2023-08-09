<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupplierOpeningBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SupplierOpeningBalance>
 */
final class SupplierOpeningBalanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierOpeningBalance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'supplier_id' => $this->faker->randomNumber(),
            'amount' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'created_by_id' => $this->faker->randomNumber(),
            'is_show_again' => $this->faker->boolean,
        ];
    }
}
