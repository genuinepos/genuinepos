<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Account>
 */
final class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_type' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'account_number' => $this->faker->word,
            'bank_id' => \App\Models\Accounts\Bank::factory(),
            'opening_balance' => $this->faker->randomFloat(),
            'debit' => $this->faker->randomFloat(),
            'credit' => $this->faker->randomFloat(),
            'balance' => $this->faker->randomFloat(),
            'remark' => $this->faker->text,
            'status' => $this->faker->boolean,
            'admin_id' => \App\Models\User::factory(),
            'branch_id' => $this->faker->randomNumber(),
        ];
    }
}
