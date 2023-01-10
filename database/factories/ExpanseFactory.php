<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Expanse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Expanse>
 */
final class ExpanseFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Expanse::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'attachment' => $this->faker->word,
            'note' => $this->faker->sentence,
            'category_ids' => $this->faker->text,
            'tax_percent' => $this->faker->randomFloat(),
            'tax_amount' => $this->faker->randomFloat(),
            'total_amount' => $this->faker->randomFloat(),
            'net_total_amount' => $this->faker->randomFloat(),
            'paid' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'admin_id' => \App\Models\User::factory(),
            'report_date' => $this->faker->word,
            'expense_account_id' => $this->faker->randomNumber(),
            'transfer_branch_to_branch_id' => $this->faker->randomNumber(),
        ];
    }
}
