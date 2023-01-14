<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Payroll;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Payroll>
 */
final class PayrollFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Payroll::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'reference_no' => $this->faker->word,
            'duration_time' => $this->faker->randomFloat(),
            'duration_unit' => $this->faker->word,
            'amount_per_unit' => $this->faker->randomFloat(),
            'total_amount' => $this->faker->randomFloat(),
            'total_allowance_amount' => $this->faker->randomFloat(),
            'total_deduction_amount' => $this->faker->randomFloat(),
            'gross_amount' => $this->faker->randomFloat(),
            'paid' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'report_date_ts' => $this->faker->word,
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'admin_id' => \App\Models\User::factory(),
        ];
    }
}
