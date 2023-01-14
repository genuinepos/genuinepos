<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupplierLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SupplierLedger>
 */
final class SupplierLedgerFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = SupplierLedger::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'branch_id' => $this->faker->randomNumber(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'purchase_id' => \App\Models\Purchase::factory(),
            'purchase_return_id' => $this->faker->randomNumber(),
            'purchase_payment_id' => \App\Models\PurchasePayment::factory(),
            'supplier_payment_id' => \App\Models\SupplierPayment::factory(),
            'row_type' => $this->faker->boolean,
            'amount' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'report_date' => $this->faker->word,
            'voucher_type' => $this->faker->word,
            'debit' => $this->faker->randomFloat(),
            'credit' => $this->faker->randomFloat(),
            'running_balance' => $this->faker->randomFloat(),
            'amount_type' => $this->faker->word,
        ];
    }
}
