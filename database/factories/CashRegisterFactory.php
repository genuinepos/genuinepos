<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CashRegister;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CashRegister>
 */
final class CashRegisterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashRegister::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_account_id' => $this->faker->randomNumber(),
            'cash_counter_id' => \App\Models\CashCounter::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'admin_id' => \App\Models\User::factory(),
            'cash_in_hand' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'closed_at' => $this->faker->word,
            'closed_amount' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean,
            'closing_note' => $this->faker->text,
        ];
    }
}
