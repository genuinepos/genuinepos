<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PurchaseReturn;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PurchaseReturn>
 */
final class PurchaseReturnFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = PurchaseReturn::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->word,
            'purchase_id' => \App\Models\Purchase::factory(),
            'admin_id' => $this->faker->randomNumber(),
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'return_type' => $this->faker->boolean,
            'total_return_amount' => $this->faker->randomFloat(),
            'total_return_due' => $this->faker->randomFloat(),
            'total_return_due_received' => $this->faker->randomFloat(),
            'purchase_tax_percent' => $this->faker->randomFloat(),
            'purchase_tax_amount' => $this->faker->randomFloat(),
            'date' => $this->faker->word,
            'month' => $this->faker->word,
            'year' => $this->faker->word,
            'report_date' => $this->faker->word,
            'purchase_return_account_id' => $this->faker->randomNumber(),
        ];
    }
}
