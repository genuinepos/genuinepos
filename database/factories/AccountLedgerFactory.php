<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AccountLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AccountLedger>
 */
final class AccountLedgerFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = AccountLedger::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'date' => $this->faker->dateTime(),
            'voucher_type' => $this->faker->word,
            'account_id' => $this->faker->randomNumber(),
            'expense_id' => $this->faker->randomNumber(),
            'expense_payment_id' => $this->faker->randomNumber(),
            'sale_id' => $this->faker->randomNumber(),
            'sale_payment_id' => $this->faker->randomNumber(),
            'supplier_payment_id' => $this->faker->randomNumber(),
            'sale_return_id' => $this->faker->randomNumber(),
            'purchase_id' => $this->faker->randomNumber(),
            'purchase_payment_id' => $this->faker->randomNumber(),
            'customer_payment_id' => $this->faker->randomNumber(),
            'purchase_return_id' => $this->faker->randomNumber(),
            'adjustment_id' => $this->faker->randomNumber(),
            'stock_adjustment_recover_id' => $this->faker->randomNumber(),
            'payroll_id' => $this->faker->randomNumber(),
            'payroll_payment_id' => $this->faker->randomNumber(),
            'production_id' => $this->faker->randomNumber(),
            'loan_id' => $this->faker->randomNumber(),
            'loan_payment_id' => $this->faker->randomNumber(),
            'contra_credit_id' => $this->faker->randomNumber(),
            'contra_debit_id' => $this->faker->randomNumber(),
            'debit' => $this->faker->randomFloat(),
            'credit' => $this->faker->randomFloat(),
            'running_balance' => $this->faker->randomFloat(),
            'amount_type' => $this->faker->word,
        ];
    }
}
