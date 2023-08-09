<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SupplierPayment>
 */
final class SupplierPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'voucher_no' => $this->faker->word,
            'reference' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'account_id' => \App\Models\Account::factory(),
            'paid_amount' => $this->faker->randomFloat(),
            'less_amount' => $this->faker->randomFloat(),
            'report_date' => $this->faker->word,
            'type' => $this->faker->boolean,
            'pay_mode' => $this->faker->word,
            'payment_method_id' => \App\Models\PaymentMethod::factory(),
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'card_no' => $this->faker->word,
            'card_holder' => $this->faker->word,
            'card_type' => $this->faker->word,
            'card_transaction_no' => $this->faker->word,
            'card_month' => $this->faker->word,
            'card_year' => $this->faker->word,
            'card_secure_code' => $this->faker->word,
            'account_no' => $this->faker->word,
            'cheque_no' => $this->faker->word,
            'transaction_no' => $this->faker->word,
            'attachment' => $this->faker->word,
            'note' => $this->faker->sentence,
            'admin_id' => $this->faker->randomNumber(),
        ];
    }
}
