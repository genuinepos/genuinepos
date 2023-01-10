<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\PayrollDeduction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\PayrollDeduction>
 */
final class PayrollDeductionFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PayrollDeduction::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'payroll_id' => $this->faker->randomNumber(),
            'deduction_name' => $this->faker->word,
            'amount_type' => $this->faker->boolean,
            'deduction_percent' => $this->faker->randomFloat(),
            'deduction_amount' => $this->faker->randomFloat(),
            'report_date_ts' => $this->faker->word,
            'is_delete_in_update' => $this->faker->boolean,
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
        ];
    }
}
