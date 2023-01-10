<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SaleReturn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SaleReturn>
 */
final class SaleReturnFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = SaleReturn::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'total_item' => $this->faker->randomNumber(),
            'total_qty' => $this->faker->randomFloat(),
            'invoice_id' => $this->faker->word,
            'sale_id' => \App\Models\Sale::factory(),
            'customer_id' => \App\Models\Customer::factory(),
            'admin_id' => $this->faker->randomNumber(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'sale_return_account_id' => $this->faker->randomNumber(),
            'return_discount_type' => $this->faker->boolean,
            'return_discount' => $this->faker->randomFloat(),
            'return_discount_amount' => $this->faker->randomFloat(),
            'return_tax' => $this->faker->randomFloat(),
            'return_tax_amount' => $this->faker->randomFloat(),
            'net_total_amount' => $this->faker->randomFloat(),
            'total_return_amount' => $this->faker->randomFloat(),
            'total_return_due' => $this->faker->randomFloat(),
            'total_return_due_pay' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'report_date' => $this->faker->word,
            'return_note' => $this->faker->text,
        ];
    }
}
