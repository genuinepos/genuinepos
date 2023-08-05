<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerLedger>
 */
final class CustomerLedgerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerLedger::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'customer_id' => \App\Models\Customer::factory(),
            'sale_id' => \App\Models\Sale::factory(),
            'sale_return_id' => $this->faker->randomNumber(),
            'sale_payment_id' => \App\Models\SalePayment::factory(),
            'customer_payment_id' => \App\Models\CustomerPayment::factory(),
            'money_receipt_id' => \App\Models\MoneyReceipt::factory(),
            'row_type' => $this->faker->boolean,
            'amount' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'report_date' => $this->faker->word,
            'is_advanced' => $this->faker->boolean,
            'voucher_type' => $this->faker->word,
            'debit' => $this->faker->randomFloat(),
            'credit' => $this->faker->randomFloat(),
            'running_balance' => $this->faker->randomFloat(),
            'amount_type' => $this->faker->word,
        ];
    }
}
