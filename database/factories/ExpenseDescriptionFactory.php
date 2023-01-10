<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ExpenseDescription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ExpenseDescription>
 */
final class ExpenseDescriptionFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = ExpenseDescription::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'expense_id' => $this->faker->randomNumber(),
            'expense_category_id' => \App\Models\ExpanseCategory::factory(),
            'amount' => $this->faker->randomFloat(),
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
