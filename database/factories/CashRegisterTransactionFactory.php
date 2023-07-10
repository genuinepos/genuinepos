<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CashRegisterTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CashRegisterTransaction>
 */
final class CashRegisterTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashRegisterTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cash_register_id' => $this->faker->randomNumber(),
            'sale_id' => \App\Models\Sale::factory(),
        ];
    }
}
