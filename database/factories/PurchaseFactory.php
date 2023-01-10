<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Purchase>
 */
final class PurchaseFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Purchase::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'pay_term' => $this->faker->boolean,
            'pay_term_number' => $this->faker->randomNumber(),
            'total_item' => $this->faker->randomNumber(),
            'net_total_amount' => $this->faker->randomFloat(),
            'order_discount' => $this->faker->randomFloat(),
            'order_discount_type' => $this->faker->boolean,
            'order_discount_amount' => $this->faker->randomFloat(),
            'shipment_details' => $this->faker->word,
            'shipment_charge' => $this->faker->randomFloat(),
            'purchase_note' => $this->faker->text,
            'purchase_tax_id' => $this->faker->randomNumber(),
            'purchase_tax_percent' => $this->faker->randomFloat(),
            'purchase_tax_amount' => $this->faker->randomFloat(),
            'total_purchase_amount' => $this->faker->randomFloat(),
            'paid' => $this->faker->randomFloat(),
            'due' => $this->faker->randomFloat(),
            'purchase_return_amount' => $this->faker->randomFloat(),
            'purchase_return_due' => $this->faker->randomFloat(),
            'payment_note' => $this->faker->text,
            'admin_id' => \App\Models\User::factory(),
            'purchase_status' => $this->faker->boolean,
            'is_purchased' => $this->faker->boolean,
            'date' => $this->faker->word,
            'delivery_date' => $this->faker->word,
            'time' => $this->faker->word,
            'report_date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'is_last_created' => $this->faker->boolean,
            'is_return_available' => $this->faker->boolean,
            'attachment' => $this->faker->word,
            'po_qty' => $this->faker->randomFloat(),
            'po_pending_qty' => $this->faker->randomFloat(),
            'po_received_qty' => $this->faker->randomFloat(),
            'po_receiving_status' => $this->faker->word,
            'purchase_account_id' => $this->faker->randomNumber(),
        ];
    }
}
