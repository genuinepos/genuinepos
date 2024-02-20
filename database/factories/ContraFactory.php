<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Contra;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Contra>
 */
final class ContraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contra::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'voucher_no' => $this->faker->word,
            'date' => $this->faker->word,
            'report_date' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'receiver_account_id' => \App\Models\Account::factory(),
            'sender_account_id' => \App\Models\Account::factory(),
            'amount' => $this->faker->randomFloat(),
            'attachment' => $this->faker->word,
            'remarks' => $this->faker->text,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
