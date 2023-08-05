<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Loan>
 */
final class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'expense_id' => $this->faker->randomNumber(),
            'purchase_id' => $this->faker->randomNumber(),
            'reference_no' => $this->faker->word,
            'loan_company_id' => \App\Models\LoanCompany::factory(),
            'account_id' => \App\Models\Account::factory(),
            'loan_account_id' => $this->faker->randomNumber(),
            'type' => $this->faker->boolean,
            'loan_amount' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'total_paid' => $this->faker->randomFloat(),
            'total_receive' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'loan_reason' => $this->faker->text,
            'loan_by' => $this->faker->word,
            'created_user_id' => $this->faker->randomNumber(),
        ];
    }
}
