<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LoanCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\LoanCompany>
 */
final class LoanCompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LoanCompany::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'pay_loan_amount' => $this->faker->randomFloat(),
            'pay_loan_due' => $this->faker->randomFloat(),
            'get_loan_amount' => $this->faker->randomFloat(),
            'get_loan_due' => $this->faker->randomFloat(),
            'total_pay' => $this->faker->randomFloat(),
            'total_receive' => $this->faker->randomFloat(),
        ];
    }
}
