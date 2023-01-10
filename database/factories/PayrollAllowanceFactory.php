<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\PayrollAllowance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\PayrollAllowance>
 */
final class PayrollAllowanceFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PayrollAllowance::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'payroll_id' => $this->faker->randomNumber(),
            'allowance_name' => $this->faker->word,
            'amount_type' => $this->faker->word,
            'allowance_percent' => $this->faker->randomFloat(),
            'allowance_amount' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'report_date_ts' => $this->faker->word,
            'is_delete_in_update' => $this->faker->boolean,
        ];
    }
}
