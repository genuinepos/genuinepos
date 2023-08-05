<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Sale>
 */
final class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'branch_id' => \App\Models\Branch::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'sale_account_id' => $this->faker->randomNumber(),
            'pay_term' => $this->faker->boolean,
            'pay_term_number' => $this->faker->randomNumber(),
            'total_item' => $this->faker->randomNumber(),
            'net_total_amount' => $this->faker->randomFloat(),
            'order_discount_type' => $this->faker->boolean,
            'order_discount' => $this->faker->randomFloat(),
            'order_discount_amount' => $this->faker->randomFloat(),
            'redeem_point' => $this->faker->randomFloat(),
            'redeem_point_rate' => $this->faker->randomFloat(),
            'shipment_details' => $this->faker->word,
            'shipment_address' => $this->faker->text,
            'shipment_charge' => $this->faker->randomFloat(),
            'shipment_status' => $this->faker->boolean,
            'delivered_to' => $this->faker->text,
            'sale_note' => $this->faker->text,
            'order_tax_percent' => $this->faker->randomFloat(),
            'order_tax_amount' => $this->faker->randomFloat(),
            'total_payable_amount' => $this->faker->randomFloat(),
            'paid' => $this->faker->randomFloat(),
            'change_amount' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'is_return_available' => $this->faker->boolean,
            'ex_status' => $this->faker->boolean,
            'sale_return_amount' => $this->faker->randomFloat(),
            'sale_return_due' => $this->faker->randomFloat(),
            'payment_note' => $this->faker->text,
            'admin_id' => \App\Models\User::factory(),
            'status' => $this->faker->boolean,
            'is_fixed_challen' => $this->faker->boolean,
            'date' => $this->faker->word,
            'time' => $this->faker->word,
            'report_date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'attachment' => $this->faker->word,
            'gross_pay' => $this->faker->randomFloat(),
            'previous_due' => $this->faker->randomFloat(),
            'all_total_payable' => $this->faker->randomFloat(),
            'previous_due_paid' => $this->faker->randomFloat(),
            'customer_running_balance' => $this->faker->randomFloat(),
            'created_by' => $this->faker->boolean,
        ];
    }
}
