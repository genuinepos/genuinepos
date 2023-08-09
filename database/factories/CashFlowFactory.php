<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CashFlow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CashFlow>
 */
final class CashFlowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashFlow::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'account_id' => \App\Models\Account::factory(),
            'sender_account_id' => \App\Models\Account::factory(),
            'receiver_account_id' => \App\Models\Account::factory(),
            'purchase_payment_id' => \App\Models\PurchasePayment::factory(),
            'supplier_payment_id' => \App\Models\SupplierPayment::factory(),
            'sale_payment_id' => \App\Models\SalePayment::factory(),
            'customer_payment_id' => \App\Models\CustomerPayment::factory(),
            'expanse_payment_id' => \App\Models\ExpansePayment::factory(),
            'money_receipt_id' => \App\Models\MoneyReceipt::factory(),
            'payroll_id' => \App\Models\Hrm\Payroll::factory(),
            'payroll_payment_id' => \App\Models\Hrm\PayrollPayment::factory(),
            'loan_id' => \App\Models\Loan::factory(),
            'debit' => $this->faker->randomFloat(),
            'credit' => $this->faker->randomFloat(),
            'balance' => $this->faker->randomFloat(),
            'transaction_type' => $this->faker->boolean,
            'cash_type' => $this->faker->boolean,
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'report_date' => $this->faker->word,
            'admin_id' => \App\Models\User::factory(),
            'related_cash_flow_id' => $this->faker->randomNumber(),
            'loan_payment_id' => \App\Models\LoanPayment::factory(),
        ];
    }
}
